<?php

import('de_brb_hvl_wur_stumml_datasheet_AbstractStationDatasheetList');

class StationDatasheetListOrderShort extends AbstractStationDatasheetList
{
    private $entries;

    public function __construct()
    {
        parent::__construct("ORDER_SHORT");
    }
    
    public function getOrderedArray($array)
    {
        return $array;
    }
}
?>
