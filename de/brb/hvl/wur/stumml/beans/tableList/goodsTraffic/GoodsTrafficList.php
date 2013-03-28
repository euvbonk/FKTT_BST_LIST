<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_xml_DatasheetElement');
import('de_brb_hvl_wur_stumml_beans_tableList_goodsTraffic_GoodsTrafficListRowEntriesImpl');

import('de_brb_hvl_wur_stumml_util_reportTable_ListRow');
import('de_brb_hvl_wur_stumml_util_reportTable_ListRowCellsImpl');

class GoodsTrafficList
{
    // table footer variables    
    private $sumInput = 0;          /* sum of all cars input a day */
    private $sumOutput = 0;         /* sum of all cars output a day */
    private $sumMaxInOut = 0;       /* sum of the max of input and output */
    private $minMinLength = array();/* min of all min length tracks */
    private $minMaxLength = array();/* min of all max length tracks */

    private $tableEntries;

    /**
     * @param array $fileList
     * @param float $dAW
     * @return GoodsTrafficList
     */
    public function __construct(array $fileList, $dAW)
    {
        $this->tableEntries = new ListRow();

        $this->buildTableEntries($fileList, $dAW);
        return $this;
    }

    /**
     * @return ListRow
     */
    public function getTableEntries()
    {
        return $this->tableEntries;
    }

    /**
     * @return ListRow
     */
    public function getTableFooter()
    {
        $l = new ListRow();
        $cells = array("&#8721;:&nbsp;",
                       sprintf(ReportTableListProperties::FORMAT, $this->sumInput),
                       sprintf(ReportTableListProperties::FORMAT, $this->sumOutput),
                       sprintf(ReportTableListProperties::FORMAT, $this->sumMaxInOut),
                       (!empty($this->minMinLength)) ? min($this->minMinLength) : 0,
                       (!empty($this->minMaxLength)) ? min($this->minMaxLength) : 0
                      );
        $s = "style=\"text-align:center;\"";
        $l->append(new ListRowCellsImpl(
            $cells, array("colspan=\"3\" style=\"text-align:right;\"", $s, $s, $s ,$s ,$s)));
        return $l;
    }

    /**
     * @param array $array
     * @param float $dAW
     */
    private function buildTableEntries($array, $dAW)
    {
        /** @var $value File */
        foreach ($array as $value)
        {
            $xml = new DatasheetElement(new SimpleXMLElement($value->getPathname(), null, true), $dAW);
            $this->tableEntries->append(
                new GoodsTrafficListRowEntriesImpl(
                                                $xml->getName(),
                                                $xml->getShort(),
                                                $value->getPathname(),
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
        }
    }

    /**
     * @return ListRow
     */
    public function getTableHeader()
    {
        $l = new ListRow();
        $l->append(
                new ListRowCellsImpl(
                    array("K&uuml;rzel", "Name", "Wagen pro Tag", "Hauptgleisl&auml;nge in cm"), 
                    array("rowspan=\"2\"", "rowspan=\"2\"", "colspan=\"3\"", "colspan=\"2\"")
                    )
                );
        $l->append(
                new ListRowCellsImpl(
                    array("Eingang", "Ausgang", "Max", "k&uuml;rzestes", "l&auml;ngstes")
                    )
                );
        return $l;
    }

    /**
     * @param float $lengthPerCar
     * @return string
     */
    public function getTrainCount($lengthPerCar)
    {
        if (empty($this->minMaxLength) || min($this->minMaxLength) == 0) return "0.0";
        return sprintf(ReportTableListProperties::FORMAT, $this->sumMaxInOut*$lengthPerCar/min($this->minMaxLength));
    }
}
