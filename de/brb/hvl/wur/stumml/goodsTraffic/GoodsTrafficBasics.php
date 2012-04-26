<?php
import('de_brb_hvl_wur_stumml_Frame');
import('de_brb_hvl_wur_stumml_FrameForm');

import('de_brb_hvl_wur_stumml_goodsTraffic_GoodsTrafficPageContent');
import('de_brb_hvl_wur_stumml_goodsTraffic_GoodsTrafficList');
import('de_brb_hvl_wur_stumml_goodsTraffic_GoodsTrafficSettings');

import('de_brb_hvl_wur_stumml_util_BasicDirectory');

import('de_brb_hvl_wur_stumml_goodsTraffic_view_GoodsTrafficListBuilder');

import('de_brb_hvl_wur_stumml_goodsTraffic_beans_DatasheetElement');

import('de_brb_hvl_wur_stumml_goodsTraffic_view_GoodsTrafficListEntryRowImpl');
import('de_brb_hvl_wur_stumml_goodsTraffic_view_GoodsTrafficListEntryFooterImpl');

class GoodsTrafficBasics extends Frame implements FrameForm, GoodsTrafficPageContent, GoodsTrafficList
{
    private static $availCommands = array('calculate', 'reset');
    private $allDatasheets = array();   // contains all available datasheets

    // form default values
    private $daysAWeek = 7;         
    private $lengthPerCar = 10.0;       // unit is cm
    private $stationFilter = array();   // contains all selected datasheets

    // form/page output values
    // table values
    private $oListTableEntries = null;
    private $oListTableFooter = null;
    // table footer variables    
    private $sumInput = 0;          /* sum of all cars input a day */
    private $sumOutput = 0;         /* sum of all cars output a day */
    private $sumMaxInOut = 0;       /* sum of the max of input and output */
    private $minMinLength = array();/* min of all min length tracks */
    private $minMaxLength = array();/* min of all max length tracks */

    public function __construct()
    {
        // set template file
        parent::__construct(GoodsTrafficSettings::getInstance()->getTemplateFile());
        // grab all datasheets
        $this->allDatasheets = BasicDirectory::scanDirectories(GoodsTrafficSettings::getInstance()->uploadDir(), array("xml"));
        $this->oListTableEntries = new GoodsTrafficListBuilder();

        $this->doCommand(common::GetCommand(), $_POST);

        $this->buildOutput();
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
            }
            else if (!array_key_exists('calculate', $DATA) && array_key_exists('reset', $DATA))
            {
                unset($DATA['check']);
                unset($DATA['filterCSV']);
            }
        }
    }

    protected function buildOutput()
    {
        $flag = false;
        foreach ($this->allDatasheets as $value)
        {
            $xml = new DatasheetElement(simplexml_load_file($value), $this->daysAWeek);
            $this->oListTableEntries->addTableRow(
                new GoodsTrafficListEntryRowImpl((!$flag) ? GoodsTrafficList::ODD : GoodsTrafficList::EVEN,
                                                (in_array($xml->getShort(), $this->stationFilter)) ? true : false,
                                                $xml->getName(),
                                                $xml->getShort(),
                                                $xml->getCarsInput(),
                                                $xml->getCarsOutput(),
                                                $xml->getCarsMax(),
                                                $xml->getShortestMainTrackLength(),
                                                $xml->getLongestMainTrackLength()
                                                )
                );
            
            $this->sumInput += $xml->getCarsInput();
            $this->sumOutput += $xml->getCarsOutput();
            $this->sumMaxInOut += $xml->getCarsMax();
            $sml = $xml->getShortestMainTrackLength();
            if (is_numeric($sml))
            {
                $this->minMinLength[] = $sml;
            }
            $lml = $xml->getLongestMainTrackLength();
            if (is_numeric($lml))
            {
                $this->minMaxLength[] = $lml;
            }
            $flag = !$flag;
        }
        
        $this->oListTableFooter =
            new GoodsTrafficListEntryFooterImpl($this->sumInput,
                                                $this->sumOutput,
                                                $this->sumMaxInOut,
                                                (!empty($this->minMinLength)) ? min($this->minMinLength) : 0,
                                                (!empty($this->minMaxLength)) ? min($this->minMaxLength) : 0
                                                );
    }

    /**
     * @see abstract class Frame
     */    
    public function getLastChangeTimestamp()
    {
        return GoodsTrafficSettings::getInstance()->lastAddonChange();
    }

    /**
     * @see Interface FrameForm
     */
    public function getFormActionUri()
    {
        return common::AbsoluteUrl(common::WhichPage());
    }

    /**
     * @see Interface GoodsTrafficPageContent
     */
    public function getDaysOfWeek()
    {
        return $this->daysAWeek;
    }
    
    /**
     * @see Interface GoodsTrafficPageContent
     */
    public function getFilterCSV()
    {
        return implode(", ", $this->stationFilter);
    }

    /**
     * @see Interface GoodsTrafficPageContent
     */
    public function getLengthPerCar()
    {
        return $this->lengthPerCar;
    }

    /**
     * @see Interface GoodsTrafficPageContent
     */
    public function getTableEntries()
    {
        return $this->oListTableEntries->getHtml();
    }

    /**
     * @see Interface GoodsTrafficPageContent
     */
    public function getTableFooter()
    {
        return $this->oListTableFooter->getHtml();
    }

    /**
     * @see Interface GoodsTrafficPageContent
     */
    public function getMinTrainCount()
    {
        if (empty($this->stationFilter)) return "Kein Filter definiert!";
        if (empty($this->minMaxLength) || min($this->minMaxLength) == 0) return "0.0";
        return sprintf(GoodsTrafficList::FORMAT, $this->sumMaxInOut*$this->lengthPerCar/min($this->minMaxLength));
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
