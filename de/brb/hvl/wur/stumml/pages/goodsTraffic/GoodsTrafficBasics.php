<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_FrameForm');

import('de_brb_hvl_wur_stumml_pages_goodsTraffic_GoodsTrafficPageContent');
import('de_brb_hvl_wur_stumml_pages_goodsTraffic_GoodsTrafficSettings');

import('de_brb_hvl_wur_stumml_beans_tableList_goodsTraffic_GoodsTrafficList');

import('de_brb_hvl_wur_stumml_util_BasicDirectory');
import('de_brb_hvl_wur_stumml_util_reportTable_ReportTableList');

class GoodsTrafficBasics extends Frame implements FrameForm, GoodsTrafficPageContent
{
    private static $EPOCHS = array('I', 'II', 'III', 'IV', 'V', 'VI');
    private static $DAYS_OF_WEEK = array(5, 5.5, 7);
    private static $availCommands = array('calculate', 'reset');
    private $allDatasheets = array();   // contains all available datasheets

    // form default values
    private $daysAWeek = 7;         
    private $lengthPerCar = 10.0;       // unit is cm
    private $stationFilter = array();   // contains all selected datasheets
    private $epoch = 4;

    // form/page output values
    // table values
    private $oListTable = null;
    private $oReportTable = null;

    public function __construct()
    {
        // set template file
        parent::__construct(GoodsTrafficSettings::getInstance()->getTemplateFile());
        $this->epoch = self::$EPOCHS[3];
        $this->daysAWeek = self::$DAYS_OF_WEEK[2];

        // grab all datasheets
        $this->allDatasheets = BasicDirectory::scanDirectories(GoodsTrafficSettings::getInstance()->uploadDir(), array("xml"));

        $this->doCommand(common::GetCommand(), $_POST);

        $this->oListTable = new GoodsTrafficList($this->allDatasheets, $this->daysAWeek);

        $this->oReportTable = new ReportTableListImpl();
        $this->oReportTable->setRowSelectorEnabled(true);
        $this->oReportTable->setTableHead($this->oListTable->getTableHeader());
        $this->oReportTable->setTableBody($this->oListTable->getTableEntries());
        $this->oReportTable->setTableFoot($this->oListTable->getTableFooter());
    }

    protected function doCommand($cmd, $DATA = array())
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && in_array($cmd, self::$availCommands))
        {
            if (array_key_exists('calculate', $DATA) && !array_key_exists('reset', $DATA))
            {
                if (isset($DATA['filterCSV']) && strlen($DATA['filterCSV']) > 0)
                {
                    $this->stationFilter = array_map('strtoupper', array_map('trim',explode(",", $DATA['filterCSV'])));
                }
                if (isset($DATA['check']))
                {
                    $this->stationFilter = array_merge($this->stationFilter, $DATA['check']);
                    // Falls jemand doch tatsächlich anhakt UND einträgt!
                    $this->stationFilter = array_unique($this->stationFilter);
                }
                $this->allDatasheets = $this->getFilteredDatasheets($this->stationFilter, $this->allDatasheets);
                $this->daysAWeek = $DATA['daysOfWeek'];
                $this->lengthPerCar = $DATA['lengthPerCar'];
                $this->epoch = $DATA['epoch'];
            }
            else if (!array_key_exists('calculate', $DATA) && array_key_exists('reset', $DATA))
            {
                unset($DATA['check']);
                unset($DATA['filterCSV']);
            }
        }
    }

    /**
     * @see abstract class Frame
     */    
    public final function getLastChangeTimestamp()
    {
        return GoodsTrafficSettings::getInstance()->lastAddonChange();
    }

    /**
     * @see Interface FrameForm
     */
    public final function getFormActionUri()
    {
        return common::AbsoluteUrl(common::WhichPage());
    }

    /**
     * @see Interface GoodsTrafficPageContent
     */
    public final function getDaysOfWeekOptionsUI()
    {
        $str = "";
        foreach (self::$DAYS_OF_WEEK as $value)
        {
            $str .= "<option".(($this->daysAWeek == $value) ? " selected=\"selected\"" : "").">".$value."</option>";
        }
        return $str;
    }
    
    /**
     * @see Interface GoodsTrafficPageContent
     */
    public final function getFilterCSV()
    {
        return implode(", ", $this->stationFilter);
    }

    /**
     * @see Interface GoodsTrafficPageContent
     */
    public final function getLengthPerCar()
    {
        return $this->lengthPerCar;
    }

    /**
     * @see Interface GoodsTrafficPageContent
     */
    public final function getTable()
    {
        return $this->oReportTable->getHtml();
    }

    /**
     * @see Interface GoodsTrafficPageContent
     */
    public final function getMinTrainCount()
    {
        if (empty($this->stationFilter)) return "Kein Filter definiert!";
        return $this->oListTable->getTrainCount($this->lengthPerCar);
    }

    /**
     * @see Interface GoodsTrafficPageContent
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

    private function getFilteredDatasheets($filter = array(), $in = array())
    {
        if (empty($filter))
        {
            return $in;
        }
        $ret = array();
        foreach ($in as $value)
        {
            if (in_array(strtoupper(basename($value, ".xml")), $filter))
            {
                $ret[] = $value;
            }
        }
        return $ret;
    }
}
?>
