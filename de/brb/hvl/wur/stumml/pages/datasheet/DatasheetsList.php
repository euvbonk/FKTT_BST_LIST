<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_FrameForm');

import('de_brb_hvl_wur_stumml_pages_datasheet_DatasheetsPageContent');
import('de_brb_hvl_wur_stumml_pages_datasheet_StationDatasheetSettings');

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');

import('de_brb_hvl_wur_stumml_beans_tableList_datasheet_StationDatasheetList');

import('de_brb_hvl_wur_stumml_util_BasicDirectory');
import('de_brb_hvl_wur_stumml_util_reportTable_ReportTableList');

class DatasheetsList extends Frame implements FrameForm, DatasheetsPageContent
{
    private static $EPOCHS = array('I', 'II', 'III', 'IV', 'V', 'VI');
    private $epoch = "IV";

    private static $ORDERS = array("ORDER_SHORT" => "K&uuml;rzel (aufsteigend)",  "ORDER_LAST" => "letzte &Auml;nderung (absteigend)");
    private $order = "ORDER_SHORT";

    private $oFileManager = null;
    private $oList = null;
    private $oReportTable = null;
    
    public function __construct()
    {
        parent::__construct(StationDatasheetSettings::getInstance()->getTemplateFile());
        $this->epoch = self::$EPOCHS[3];
        //$this->order = array_keys(self::$ORDERS){0};

        $fileManager = new FileManagerImpl();

        $this->doCommand(common::GetCommand(), $_POST);

        $this->oList = new StationDatasheetList(
            $fileManager->getFilesFromEpochWithOrder($this->epoch, $this->order), $this->order);

        $this->oReportTable = new ReportTableListImpl();
        $this->oReportTable->setTableHead($this->oList->getTableHeader());
        $this->oReportTable->setTableBody($this->oList->getTableEntries());
    }

    protected function doCommand($cmd, $DATA = array())
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            if (array_key_exists('startFilter', $DATA) && !array_key_exists('reset', $DATA))
            {
                $this->epoch = $DATA['epoch'];
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
     * @see Interface FrameForm
     */
    public final function getFormActionUri()
    {
        return common::AbsoluteUrl(common::WhichPage());
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
    public final function getEpochOptionsUI()
    {
        $str = "";
        foreach (self::$EPOCHS as $value)
        {
            $str .= "<option".(($this->epoch == $value) ? " selected=\"selected\"" : "").">".$value."</option>";
        }
        return $str;
    }
    
    /**
     * @see Interface DatasheetsPageContent
     */
    public final function getTable()
    {
        return $this->oReportTable->getHtml();
    }
}
?>
