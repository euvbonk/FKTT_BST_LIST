<?php

import('de_brb_hvl_wur_stumml_pages_AbstractList');

import('de_brb_hvl_wur_stumml_pages_datasheet_DatasheetsPageContent');
import('de_brb_hvl_wur_stumml_pages_datasheet_StationDatasheetSettings');

import('de_brb_hvl_wur_stumml_beans_tableList_datasheet_StationDatasheetList');
import('de_brb_hvl_wur_stumml_cmd_YellowPageCmd');
import('de_brb_hvl_wur_stumml_cmd_CSVListCmd');
import('de_brb_hvl_wur_stumml_cmd_ZipBundleCmd');

final class DatasheetsList extends AbstractList implements DatasheetsPageContent
{
    private static $ORDERS = array("ORDER_SHORT" => "K&uuml;rzel (aufsteigend)",
        "ORDER_LAST" => "letzte &Auml;nderung (absteigend)");
    private $order = "ORDER_SHORT";

    private $oList = null;

    public function __construct()
    {
        parent::__construct('datasheets_list');

        $this->doCommand($_POST);

        $this->oList = new StationDatasheetList($this->getFileManager()
                ->getFilesFromEpochWithOrder($this->getEpoch(), $this->order), $this->order);

        $this->getReportTable()->setTableHead($this->oList->getTableHeader());
        $this->getReportTable()->setTableBody($this->oList->getTableEntries());
        return $this;
    }

    protected function doCommand($DATA = array())
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

    protected function getCallableMethods()
    {
        return array_merge(parent::getCallableMethods(), array('getOrderOptionsUI','getYellowPageLink','getCSVListLink','getZipBundleLink','getApplicationUrl'));
    }

    /**
     * @see Interface DatasheetsPageContent
     */
    public final function getOrderOptionsUI()
    {
        $str = "";
        foreach (self::$ORDERS as $key => $value)
        {
            $str .= "<option value=\"".$key."\"".(($this->order == $key) ? " selected=\"selected\"" : "").">".$value.
                    "</option>";
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
     * @see Interface DatasheetsPageContent
     */
    public final function getCSVListLink()
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
     * @see Interface DatasheetsPageContent
     */
    public final function getZipBundleLink()
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
     * @see Interface DatasheetsPageContent
     * @return String Uri
     */
    public final function getApplicationUrl()
    {
        $cmd = new CheckJNLPVersionCmd("editor");
        return ($cmd->doCommand()) ? $cmd->getDeploy() : "";
    }
}
