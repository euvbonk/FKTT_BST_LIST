<?php

import('de_brb_hvl_wur_stumml_datasheet_AbstractStationDatasheetList');

class StationDatasheetListOrderLast extends AbstractStationDatasheetList
{
    private $entries;

    public function __construct()
    {
        parent::__construct("ORDER_LAST");
    }
    
    public function getOrderedArray($array)
    {
        $test = $array;
        usort($test, array(__CLASS__, "compare"));
        return array_reverse($test);
    }
    
    private static function compare($a, $b)
    {
        $time_a = filemtime($a);
        $time_b = filemtime($b);
        if ($time_a == $time_b)
        {
            return 0;
        }
        return ($time_a > $time_b) ? +1 : -1;
    }
}
?>
