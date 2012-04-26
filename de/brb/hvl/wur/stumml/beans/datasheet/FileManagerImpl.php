<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');
import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_util_BasicDirectory');

class FileManagerImpl implements FileManager
{
    public static $EPOCHS = array('I', 'II', 'III', 'IV', 'V', 'VI');
    private $allDatasheets = array();

    public function __construct()
    {
        // grab all datasheets for all epochs
        $all = BasicDirectory::scanDirectories(Settings::uploadDir(), array("xml"));
        foreach (self::$EPOCHS as $epoch)
        {
            $this->allDatasheets[$epoch] = $this->getFilesFromEpoch($all, $epoch);
        }
        //print "<pre>".print_r($this->allDatasheets, true)."</pre>";
    }

    public function getFilesFromEpoch($in, $epoch = "IV")
    {
        $ret = array();
        foreach ($in as $fileUrl)
        {
            if ($this->endsWith("-".$epoch.".xml", $fileUrl) || ($epoch == "IV" && !$this->contains("-", $fileUrl)))
            {
                $ret[basename($fileUrl,".xml")] = $fileUrl;
            }
        }
        return $ret;
    }

    public function getFilesFromEpochWithOrder($epoch = "IV", $order = "ORDER_SHORT")
    {
        $test = $this->allDatasheets[$epoch];
        if ($order == "ORDER_SHORT")
        {
            return $test;
        }
        else if ($order == "ORDER_LAST")
        {
            usort($test, array(__CLASS__, "compareTime"));
            return $test;
        }
        else
        {
            return null;
        }
    }

    public function getFilesFromEpochWithFilter($epoch = "IV", $filter = array())
    {
        if (empty($filter) && ($epoch == self::$EPOCHS[3]))
        {
            return $this->allDatasheets[$epoch];
        }
        $ret = array();
        foreach ($this->allDatasheets[self::$EPOCHS[3]] as $short => $url)
        {
            // Falls spezielle Epoche gewünscht, dann wenn vorhanden nutzen
            // anderenfalls die Default-Epoche 4 anzeigen
            if (($epoch != self::$EPOCHS[3]) && array_key_exists($short."-".$epoch, $this->allDatasheets[$epoch]))
            {
                $ret[$short] = $this->allDatasheets[$epoch][$short."-".$epoch];
            }
            else
            {
                $ret[$short] = $url;
            }
            // ist der Filter nicht leer, dann entsprechenden Wert aus
            // dem Array wieder entfernen, falls nicht im Filter
            if (!empty($filter) && !in_array(strtoupper($short), $filter))
            {
                unset($ret[$short]);
            }
        }
        return $ret;
    }


    protected function getFilteredDatasheets($filter = array(), $in = array())
    {
        if (empty($filter))
        {
            return $in;
        }
        $ret = array();
        foreach ($in as $short => $url)
        {
            //$needle = strtoupper(basename($url, ".xml"));
            /*if ($this->contains("-", $needle))
            {
                $needle = substr($needle, 0, strpos($needle, "-"));
            }*/
            if (in_array(strtoupper($short), $filter))
            {
                $ret[$short] = $url;
            }
        }
        return $ret;
    }

    protected static function compareTime($a, $b)
    {
        $time_a = filemtime($a);
        $time_b = filemtime($b);
        if ($time_a == $time_b)
        {
            return 0;
        }
        return ($time_a < $time_b) ? +1 : -1;
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
