<?php

import('de_brb_hvl_wur_stumml_beans_datasheet_FileManager');
import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_util_XmlListingFileFilter');

class FileManagerImpl implements FileManager
{
    public static $EPOCHS = array('I', 'II', 'III', 'IV', 'V', 'VI');
    private $allDatasheets = array();

    /**
     * @return FileManagerImpl
     */
    public function __construct()
    {
        // grab all datasheets for all epochs
        $f = new File(Settings::uploadDir());
        $all = $f->listFiles('XmlListingFileFilter');
        foreach (self::$EPOCHS as $epoch)
        {
            $this->allDatasheets[$epoch] = $this->getFilesFromEpoch($all, $epoch);
        }
        //print "<pre>".print_r($this->allDatasheets, true)."</pre>";
        return $this;
    }

    /**
     * @param string $epoch
     * @return File|null
     */
    public function getLatestFileFromEpoch($epoch)
	{
		// sollte die uebergebene Epoche nicht existieren
        if (!in_array($epoch, self::$EPOCHS)) {
            return null;
        }
		$t = $this->getFilesFromEpochWithOrder($epoch, "ORDER_LAST");
		// im Array steht dann an nullter Position die Datei in der die
		// letzte Aenderung stattgefunden hat
		return (!empty($t)) ? $t[0] : null;
	}

    /**
     * @param iterator $in
     * @param string   $epoch [optional]
     * @return array
     */
    public function getFilesFromEpoch($in, $epoch = "IV")
    {
        $ret = array();
        /** @var $fileUrl File */
        foreach ($in as $fileUrl)
        {
            if ($fileUrl->endsWith("-".$epoch.".xml") || ($epoch == "IV" && !$fileUrl->contains("-")))
            {
                $ret[$fileUrl->getBasename(".xml")] = $fileUrl;
            }
        }
        // iterator result is not ordered! Ordering by short means sort by key
        ksort($ret);
        return $ret;
    }

    /**
     * @param string $epoch
     * @param string $order
     * @return array Files|null
     */
    public function getFilesFromEpochWithOrder($epoch = "IV", $order = "ORDER_SHORT")
    {
        $test = $this->allDatasheets[$epoch];
        if ($order == "ORDER_SHORT")
        {
            return $test;
        }
        else if ($order == "ORDER_LAST")
        {
            usort($test, array("File", "compareLastModified"));
            return $test;
        }
        else
        {
            return null;
        }
    }

    /**
     * @param string $epoch
     * @param array  $filter
     * @return array Files
     */
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

    /**
     * @param array $filter [optional]
     * @param array $in [optional]
     * @return array
     */
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
}
