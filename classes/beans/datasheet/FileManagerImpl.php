<?php
namespace org\fktt\bstlist\beans\datasheet;

\import('beans_datasheet_FileManager');
\import('io_File');
\import('util_SorterFactory');
\import('util_XmlListingFileFilter');

use org\fktt\bstlist\io\File;
use /** @noinspection PhpUnusedAliasInspection */
    org\fktt\bstlist\util\XmlListingFileFilter;
use /** @noinspection PhpUnusedAliasInspection */
    org\fktt\bstlist\util\SorterFactory;

class FileManagerImpl implements FileManager
{
    public static $EPOCHS = array('I', 'II', 'III', 'IV', 'V', 'VI');
    private $allDatasheets = array();
    private $allCountryCodes = array();

    /**
     * @return FileManagerImpl
     */
    public function __construct()
    {
        // grab all datasheets for all epochs
        $f = new File('db');
        $all = $f->listFiles('org\fktt\bstlist\util\XmlListingFileFilter');
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
        if (!\in_array($epoch, self::$EPOCHS))
        {
            return null;
        }
        $t = $this->getFilesFromEpochWithOrder($epoch, "ORDER_LAST");
        // im Array steht dann an nullter Position die Datei in der die
        // letzte Aenderung stattgefunden hat
        return (!empty($t)) ? \array_shift($t) : null;
    }

    /**
     * @param \iterator $in
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
                $countryCode = $fileUrl->getParentFile()->getName();
                $ret[$countryCode."-".$fileUrl->getBasename(".xml")] = $fileUrl;
                // save the iso country code for internal use
                if (!\in_array($countryCode, $this->allCountryCodes))
                {
                    \array_push($this->allCountryCodes, $countryCode);
                }
            }
        }
        // iterator result is not ordered! Ordering by short means sort by key
        \ksort($ret);
        return $ret;
    }

    /**
     * @param string $epoch
     * @param string $order
     * @param string $country
     * @return array Files|null
     */
    public function getFilesFromEpochWithOrder($epoch = "IV", $order = "ORDER_SHORT", $country = null)
    {
        $test = $this->allDatasheets[$epoch];
        //print "<pre>".\print_r($test, true)."</pre>";
        if ($country != null)
        {
            //print "<pre>".$country."</pre>";
            //print "<pre>".\print_r(\array_filter($test, function($item) use (&$test, $country) { $key = \explode("-", \key($test)); print $item."=>".($key[0] == $country)."<br>"; \next($test); return $key[0] == $country;}), true)."</pre>";
            $test = \array_filter($test, function() use (&$test, $country)
            {
                // the key always is of form: ISO_COUNTRY_CODE-STATION_SHORT-EPOCH
                // so split the key at "-"...
                $key = \explode("-", \key($test));
                // ...move on to the next key in the array...
                \next($test);
                // ...and use the first element in key array for comparison
                return $key[0] == $country;
            });
        }
        switch ($order)
        {
            case "ORDER_SHORT" :
                \uasort($test, array("org\\fktt\\bstlist\\util\\SorterFactory", "compareStationShort"));
                return $test;
            case "ORDER_LAST" :
                \uasort($test, array("org\\fktt\\bstlist\\io\\File", "compareLastModified"));
                return $test;
            case "ORDER_NAME":
                \uasort($test, array("org\\fktt\\bstlist\\util\\SorterFactory", "compareStationName"));
                return $test;
            default :
                return null;
        }
    }

    /**
     * @param string $epoch
     * @param array  $filter
     * @param array  $country
     * @return array Files
     */
    public function getFilesFromEpochWithFilter($epoch = "IV", $filter = array(), $country = null)
    {
        if (empty($filter) && ($epoch == self::$EPOCHS[3]))
        {
            $test = $this->allDatasheets[$epoch];
            //print "<pre>".\print_r($test, true)."</pre>";
            if ($country != null && \is_array($country))
            {
                //print "<pre>".$country."</pre>";
                //print "<pre>".\print_r(\array_filter($test, function($item) use (&$test, $country) { $key = \explode("-", \key($test)); print $item."=>".($key[0] == $country)."<br>"; \next($test); return $key[0] == $country;}), true)."</pre>";
                $test = \array_filter($test, function() use (&$test, $country)
                {
                    // the key always is of form: ISO_COUNTRY_CODE-STATION_SHORT-EPOCH
                    // so split the key at "-"...
                    $key = \explode("-", \key($test));
                    // ...move on to the next key in the array...
                    \next($test);
                    // ...and use the first element in key array for comparison
                    return \in_array($key[0], $country);
                });
            }
            return $test;
        }
        $ret = array();
        foreach ($this->allDatasheets[self::$EPOCHS[3]] as $short => $url)
        {
            // Falls spezielle Epoche gewünscht, dann wenn vorhanden nutzen
            // anderenfalls die Default-Epoche 4 anzeigen
            if (($epoch != self::$EPOCHS[3]) && \array_key_exists($short."-".$epoch, $this->allDatasheets[$epoch]))
            {
                $ret[$short] = $this->allDatasheets[$epoch][$short."-".$epoch];
            }
            else
            {
                $ret[$short] = $url;
            }
            // ist der Filter nicht leer, dann entsprechenden Wert aus
            // dem Array wieder entfernen, falls nicht im Filter
            if ((!empty($filter) && !\in_array($short, $filter)) || ($country != null && \is_array($country) && !\in_array(\explode("-", $short)[0], $country)))
            {
                unset($ret[$short]);
            }
        }
        return $ret;
    }

    public function getAllCountryCodes()
    {
        return $this->allCountryCodes;
    }

    /**
     * @param array $filter [optional]
     * @param array $in     [optional]
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
            if (\in_array(\strtoupper($short), $filter))
            {
                $ret[$short] = $url;
            }
        }
        return $ret;
    }
}
