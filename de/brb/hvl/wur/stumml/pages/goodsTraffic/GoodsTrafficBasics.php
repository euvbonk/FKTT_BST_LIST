<?php
namespace org\fktt\bstlist\pages\goodsTraffic;

import('de_brb_hvl_wur_stumml_pages_AbstractList');

import('de_brb_hvl_wur_stumml_beans_tableList_goodsTraffic_GoodsTrafficList');

import('de_brb_hvl_wur_stumml_util_QI');
use org\fktt\bstlist\pages\AbstractList;
use org\fktt\bstlist\util\QI;
use org\fktt\bstlist\beans\datasheet\tableList\goodsTraffic\GoodsTrafficList;

class GoodsTrafficBasics extends AbstractList
{
    private static $DAYS_OF_WEEK = array(5, 5.5, 7);
    private static $availCommands = array('calculate', 'reset');

    // form default values
    private $daysAWeek = 7;
    private $lengthPerCar = 10.0; // unit is cm
    private $stationFilter = array(); // contains all selected datasheets

    // form/page output values
    // table values
    private $oListTable = null;

    public function __construct()
    {
        // set template file
        parent::__construct('goods_traffic_basics');
        $this->daysAWeek = self::$DAYS_OF_WEEK[2];

        $this->doCommand(QI::getCommand(), $_POST);

        $this->oListTable = new GoodsTrafficList($this->daysAWeek);
        $this->oListTable->buildTableEntries($this->getFileManager()
                        ->getFilesFromEpochWithFilter($this->getEpoch(), $this->stationFilter));

        $this->getReportTable()->setRowSelectorEnabled(true);
        $this->getReportTable()->setTableHead($this->oListTable->getTableHeader());
        $this->getReportTable()->setTableBody($this->oListTable->getTableEntries());
        $this->getReportTable()->setTableFoot($this->oListTable->getTableFooter());
        return $this;
    }

    protected function doCommand($cmd, $DATA = array())
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && \in_array($cmd, self::$availCommands))
        {
            if (\array_key_exists('calculate', $DATA) && !\array_key_exists('reset', $DATA))
            {
                if (isset($DATA['filterCSV']) && \strlen($DATA['filterCSV']) > 0)
                {
                    $this->stationFilter = \array_map('strtoupper', \array_map('trim', \explode(",", $DATA['filterCSV'])));
                }
                if (isset($DATA['check']))
                {
                    $this->stationFilter = \array_merge($this->stationFilter, $DATA['check']);
                    // Falls jemand doch tatsächlich anhakt UND einträgt!
                    $this->stationFilter = \array_unique($this->stationFilter);
                }
                $this->daysAWeek = $DATA['daysOfWeek'];
                $this->lengthPerCar = $DATA['lengthPerCar'];
                $this->setEpoch($DATA['epoch']);
            }
            else if (!\array_key_exists('calculate', $DATA) && \array_key_exists('reset', $DATA))
            {
                unset($DATA['check']);
                unset($DATA['filterCSV']);
            }
        }
    }

    protected function getCallableMethods()
    {
        return \array_merge(parent::getCallableMethods(), array('DaysOfWeekOptionsUI','FilterCSV','LengthPerCar','MinTrainCount'));
    }

    /**
     * @return String
     */
    public final function DaysOfWeekOptionsUI()
    {
        $str = "";
        foreach (self::$DAYS_OF_WEEK as $value)
        {
            $str .= "<option".(($this->daysAWeek == $value) ? " selected=\"selected\"" : "").">".$value."</option>";
        }
        return $str;
    }

    /**
     * @return String
     */
    public final function FilterCSV()
    {
        return \implode(", ", $this->stationFilter);
    }

    /**
     * @return float
     */
    public final function LengthPerCar()
    {
        return $this->lengthPerCar;
    }

    /**
     * @return float|String
     */
    public final function MinTrainCount()
    {
        if (empty($this->stationFilter))
        {
            return "Kein Filter definiert!";
        }
        return $this->oListTable->getTrainCount($this->lengthPerCar);
    }
}
