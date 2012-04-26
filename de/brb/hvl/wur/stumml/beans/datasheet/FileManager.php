<?php

interface FileManager
{
//    public function getFilesFromEpoch($epoch = "IV");
//    public function getFilesFromEpochAndFilter($filter, $epoch = "IV");

    // These two functions are for the listing part
    // function returns all datasheets with the given epoch ordered by last file change descending
    public function getFilesFromEpochWithOrder($epoch = "IV", $order = "ORDER_SHORT");
    // function returns all datasheets with the given epoch ordered by short ascending
    //public function getFilesFromEpochOrderShort($epoch = "IV");
}

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_util_BasicDirectory');

class FileManagerImpl implements FileManager
{
    private $allDatasheets;

    public function __construct()
    {
        // grab all datasheets for all epochs
        $this->allDatasheets = BasicDirectory::scanDirectories(Settings::uploadDir(), array("xml"));
    }

    public function getFilesFromEpoch($epoch = "IV")
    {
        $ret = array();
        foreach ($this->allDatasheets as $fileUrl)
        {
            if ($this->endsWith("-".$epoch.".xml", $fileUrl) || ($epoch == "IV" && !$this->contains("-", $fileUrl)))
            {
                $ret[] = $fileUrl;
            }
        }
        return $ret;
    }

    public function getFilesFromEpochWithOrder($epoch = "IV", $order = "ORDER_SHORT")
    {
        $test = $this->getFilesFromEpoch($epoch);
        if ($order == "ORDER_SHORT")
        {
            return $test;
        }
        else if ($order == "ORDER_LAST")
        {
            usort($test, array(__CLASS__, "compare"));
            return array_reverse($test);
        }
        else
        {
            return null;
        }
    }

    protected static function compare($a, $b)
    {
        $time_a = filemtime($a);
        $time_b = filemtime($b);
        if ($time_a == $time_b)
        {
            return 0;
        }
        return ($time_a > $time_b) ? +1 : -1;
    }

    protected function endsWith($needle, $haystack) 
    {
        return preg_match("/".preg_quote($needle) .'$/', $haystack);
    }

    protected function contains($needle, $haystack)
    {
        return (strpos($haystack,$needle)!==false) ? true : false;
    }
}

?>
