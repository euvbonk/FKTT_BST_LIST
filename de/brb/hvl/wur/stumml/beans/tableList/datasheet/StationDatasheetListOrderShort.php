<?php

import('de_brb_hvl_wur_stumml_beans_tableList_datasheet_AbstractStationDatasheetList');

class StationDatasheetListOrderShort extends AbstractStationDatasheetList
{
    private $entries;

    public function __construct(array $fileList)
    {
        parent::__construct($fileList, 3, "ASC");
    }
    
    public function getOrderedArray($array)
    {
        return $array;
    }
}
?>
