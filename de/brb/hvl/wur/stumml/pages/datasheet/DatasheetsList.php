<?php

import('de_brb_hvl_wur_stumml_pages_AbstractList');

import('de_brb_hvl_wur_stumml_pages_datasheet_DatasheetsPageContent');
import('de_brb_hvl_wur_stumml_pages_datasheet_StationDatasheetSettings');

import('de_brb_hvl_wur_stumml_beans_tableList_datasheet_StationDatasheetList');
import('de_brb_hvl_wur_stumml_cmd_YellowPageCmd');
import('de_brb_hvl_wur_stumml_cmd_CSVListCmd');

class DatasheetsList extends AbstractList implements DatasheetsPageContent
{
    private static $ORDERS = array("ORDER_SHORT" => "K&uuml;rzel (aufsteigend)",  "ORDER_LAST" => "letzte &Auml;nderung (absteigend)");
    private $order = "ORDER_SHORT";

    private $oList = null;
    
    public function __construct()
    {
        parent::__construct(StationDatasheetSettings::getInstance()->getTemplateFile());
        //$this->order = array_keys(self::$ORDERS){0};

        $this->doCommand(common::GetCommand(), $_POST);

        $this->oList = new StationDatasheetList(
            $this->getFileManager()->getFilesFromEpochWithOrder($this->getEpoch(), $this->order), $this->order);

        $this->getReportTable()->setTableHead($this->oList->getTableHeader());
        $this->getReportTable()->setTableBody($this->oList->getTableEntries());
    }

    protected function doCommand($cmd, $DATA = array())
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (array_key_exists('startFilter', $DATA) && !array_key_exists('reset', $DATA))
            {
                $this->setEpoch($DATA['epoch']);
                $this->order = $DATA['order'];
            }
            else if (!array_key_exists('startFilter', $DATA) && array_key_exists('reset', $DATA))
            {
                unset($DATA['epoch']);
                unset($DATA['order']);
            }
        }
    }

    /**
     * @see abstract class Frame
     */    
    public final function getLastChangeTimestamp()
    {
        return StationDatasheetSettings::getInstance()->lastAddonChange();
    }
    
    /**
     * @see Interface DatasheetsPageContent
     */
    public final function getOrderOptionsUI()
    {
        $str = "";
        foreach (self::$ORDERS as $key => $value)
        {
            $str .= "<option value=\"".$key."\"".(($this->order == $key) ? " selected=\"selected\"" : "").">".$value."</option>";
        }
        return $str;
    }

    /**
     * @see Interface DatasheetsPageContent
     */
    public final function getYellowPageLink()
    {
        $t = new YellowPageCmd($this->getFileManager());
        $t->doCommand($this->getEpoch());
        $l = StationDatasheetSettings::buildDownloadPath($t->getFileName(), "Gelbe Seiten für die Epoche ".$this->getEpoch());
        $l .= "&nbsp;(".date("D, d. M Y H:i", filemtime($t->getFileName())).")";
        return $l;
    }

    /**
     * @see Interface DatasheetsPageContent
     */
    public final function getCSVListLink()
    {
        $t = new CSVListCmd($this->getFileManager());
        $t->doCommand();
        if (file_exists($t->getFileName()))
        {
            $l = StationDatasheetSettings::buildDownloadPath($t->getFileName(), "Liste mit Namen und Kürzel als CSV");
            $l .= "&nbsp;(".date("D, d. M Y H:i", filemtime($t->getFileName())).")";
            return $l;
        }
        else
        {
            return "";
        }
    }
}
?>
