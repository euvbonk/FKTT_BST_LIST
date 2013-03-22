<?php

import('de_brb_hvl_wur_stumml_pages_AbstractList');

import('de_brb_hvl_wur_stumml_pages_goodsTraffic_GoodsTrafficPageContent');
import('de_brb_hvl_wur_stumml_pages_goodsTraffic_GoodsTrafficSettings');

import('de_brb_hvl_wur_stumml_beans_tableList_goodsTraffic_GoodsTrafficList');

import('de_brb_hvl_wur_stumml_util_QI');

class GoodsTrafficBasics extends AbstractList implements GoodsTrafficPageContent
{
    private static $DAYS_OF_WEEK = array(5, 5.5, 7);
    private static $availCommands = array('calculate', 'reset');

    // form default values
    private $daysAWeek = 7;         
    private $lengthPerCar = 10.0;       // unit is cm
    private $stationFilter = array();   // contains all selected datasheets

    // form/page output values
    // table values
    private $oListTable = null;

    public function __construct()
    {
        // set template file
        parent::__construct(GoodsTrafficSettings::getInstance()->getTemplateFile());
        $this->daysAWeek = self::$DAYS_OF_WEEK[2];

        $this->doCommand(QI::getCommand(), $_POST);

        $this->oListTable = new GoodsTrafficList(
            $this->getFileManager()->getFilesFromEpochWithFilter($this->getEpoch(), $this->stationFilter),
            $this->daysAWeek);

        $this->getReportTable()->setRowSelectorEnabled(true);
        $this->getReportTable()->setTableHead($this->oListTable->getTableHeader());
        $this->getReportTable()->setTableBody($this->oListTable->getTableEntries());
        $this->getReportTable()->setTableFoot($this->oListTable->getTableFooter());
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
                $this->daysAWeek = $DATA['daysOfWeek'];
                $this->lengthPerCar = $DATA['lengthPerCar'];
                $this->setEpoch($DATA['epoch']);
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
    public final function getMinTrainCount()
    {
        if (empty($this->stationFilter)) return "Kein Filter definiert!";
        return $this->oListTable->getTrainCount($this->lengthPerCar);
    }
}
