<?php
namespace org\fktt\bstlist\pages\datasheet;

\import('beans_tableList_datasheet_StationDatasheetList');
\import('cmd_YellowPageCmd');
\import('cmd_CSVListCmd');
\import('cmd_ZipBundleCmd');
\import('pages_AbstractList');

use Exception;
use org\fktt\bstlist\beans\tableList\datasheet\StationDatasheetList;
use org\fktt\bstlist\cmd\CheckJNLPVersionCmd;
use org\fktt\bstlist\cmd\CSVListCmd;
use org\fktt\bstlist\cmd\YellowPageCmd;
use org\fktt\bstlist\cmd\ZipBundleCmd;
use org\fktt\bstlist\pages\AbstractList;

final class DatasheetsList extends AbstractList
{
    private static $ORDERS = array("ORDER_SHORT" => "K&uuml;rzel (aufsteigend)",
        "ORDER_LAST" => "letzte &Auml;nderung (absteigend)");
    private $order = "ORDER_SHORT";

    private $oList = null;
    private $oEditor = null;

    public function __construct()
    {
        parent::__construct('datasheets_list');

        $this->doCommand($_POST);

        $this->oEditor = new CheckJNLPVersionCmd('editor');

        $this->oList = new StationDatasheetList($this->oEditor->isEditorPresent(), $this->order);
        $this->oList->buildTableEntries($this->getFileManager()
                ->getFilesFromEpochWithOrder($this->getEpoch(), $this->order));

        $this->getReportTable()->setTableHead($this->oList->getTableHeader());
        $this->getReportTable()->setTableBody($this->oList->getTableEntries());
        return $this;
    }

    protected function doCommand($DATA = array())
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (\array_key_exists('startFilter', $DATA) && !\array_key_exists('reset', $DATA))
            {
                $this->setEpoch($DATA['epoch']);
                $this->order = $DATA['order'];
            }
            else if (!\array_key_exists('startFilter', $DATA) && \array_key_exists('reset', $DATA))
            {
                unset($DATA['epoch']);
                unset($DATA['order']);
            }
        }
    }

    protected function getCallableMethods()
    {
        return \array_merge(parent::getCallableMethods(),
            array('OrderOptionsUI', 'YellowPageLink', 'CSVListLink', 'ZipBundleLink', 'ApplicationUrl', 'SheetViewUrl'));
    }

    /**
     * @return String
     */
    public final function OrderOptionsUI()
    {
        $str = "";
        foreach (self::$ORDERS as $key => $value)
        {
            $str .= "<option value=\"".$key."\"".(($this->order == $key) ? " selected=\"selected\"" : "").">".
                    $value."</option>";
        }
        return $str;
    }

    /**
     * @return String
     */
    public final function YellowPageLink()
    {
        $t = new YellowPageCmd($this->getFileManager());
        $t->doCommand($this->getEpoch());
        if ($t->getFile()->exists())
        {
            return $t->getFile()->toDownloadLink("Gelbe Seiten für die Epoche ".$this->getEpoch());
        }
        else
        {
            return "";
        }
    }

    /**
     * @return String
     */
    public final function CSVListLink()
    {
        $t = new CSVListCmd($this->getFileManager());
        $t->doCommand();
        if ($t->getFile()->exists())
        {
            return $t->getFile()->toDownloadLink("Liste mit Namen und Kürzel als CSV");
        }
        else
        {
            return "";
        }
    }

    /**
     * @return String
     */
    public final function ZipBundleLink()
    {
        $t = new ZipBundleCmd($this->getFileManager());
        try
        {
            $t->doCommand();
            if ($t->getFile()->exists())
            {
                return $t->getFile()->toDownloadLink("Archiv mit allen Datenblättern und Gelben Seiten");
            }
            else
            {
                return "";
            }
        }
        catch (Exception $e)
        {
            return "";
        }
    }

    /**
     * @return String Uri
     */
    public final function ApplicationUrl()
    {
        return ($this->oEditor->doCommand()) ? $this->oEditor->getDeploy() : "";
    }

    /**
     * @return String Uri
     */
    public final function SheetViewUrl()
    {
        return \substr($this->FormActionUri(), 0, \strrpos($this->FormActionUri(),"/") + 1)."Sheet_View?";
    }
}
