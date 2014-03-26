<?php
namespace org\fktt\bstlist\beans\datasheet\xml;

import('de_brb_hvl_wur_stumml_beans_datasheet_xml_BaseElement');
use SimpleXMLElement;

class DatasheetElement extends BaseElement
{
    private $daysAWeek = 7;
    private $oMainTracks = array();

    /**
     * @param SimpleXMLElement $xml
     * @param int              $days [optional]
     * @return DatasheetElement
     */
    public function __construct(SimpleXMLElement $xml, $days = 7)
    {
        parent::__construct($xml);
        $this->daysAWeek = $days;
        $this->oMainTracks = $this->getAllLengthInCm($this->getElement()->xpath("gleise/hgleise/gleis/laenge"));
        return $this;
    }

    /**
     * @return string
     */
    public function getShort()
    {
        return $this->getValueForTag('kuerzel');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getValueForTag('name');
    }

    /**
     * Also have a look at the bahnhof.xsl, where this code is adapted from!
     * @return float
     */
    public function getCarsInput()
    {
        $f = $this->summe($this->getElement()->xpath("gv/verlader/empfang/ladegut/wagen[@zeitraum='tag']"));
        $g = $this->summe($this->getElement()->xpath("gv/verlader/empfang/ladegut/wagen"));
        $h = $this->summe($this->getElement()->xpath("gv/verlader/empfang/ladegut/wagen[@zeitraum='tag']"));
        $s = $f + ($g-$h)/$this->daysAWeek;
        //print $this->getName().": ".$f." + (".$g." - ".$h.") / ".$this->daysAWeek." = ".$s."<br/>";
        return $s;
    }

    /**
     * Also have a look at the bahnhof.xsl, where this code is adapted from!
     * @return float
     */
    public function getCarsOutput()
    {
        $f = $this->summe($this->getElement()->xpath("gv/verlader/versand/ladegut/wagen[@zeitraum='tag']"));
        $g = $this->summe($this->getElement()->xpath("gv/verlader/versand/ladegut/wagen"));
        $h = $this->summe($this->getElement()->xpath("gv/verlader/versand/ladegut/wagen[@zeitraum='tag']"));
        $s = $f + ($g-$h)/$this->daysAWeek;
        //print $this->getName().": ".$f." + (".$g." - ".$h.") / ".$this->daysAWeek." = ".$s."<br/>";
        return $s;
    }

    /**
     * @return float
     */
    public function getCarsMax()
    {
        return \max($this->getCarsInput(), $this->getCarsOutput());
    }

    /**
     * @return float|string
     */
    public function getLongestMainTrackLength()
    {
        return (!empty($this->oMainTracks)) ? \max($this->oMainTracks) : "kA";
    }

    /**
     * @return float|string
     */
    public function getShortestMainTrackLength()
    {
        return (!empty($this->oMainTracks)) ? \min($this->oMainTracks) : "kA";
    }

    /**
     * @param $array
     * @return float
     */
    private function summe($array)
    {
        $ret = 0.0;
        //print "<pre>".print_r($array, true)."</pre>";
        if (!empty($array))
        {
            /** @var $value SimpleXMLElement */
            foreach($array as $value)
            {
                //print "<pre>".print_r($value, true)."</pre>";
                $ret += \floatval($value[0]);
            }
        }
        return $ret;
    }

    /**
     * @param $array
     * @return array float values
     */
    private function getAllLengthInCm($array)
    {
        $ret = array();
        /** @var $value SimpleXMLElement */
        foreach ($array as $value)
        {
            //print "<pre>".print_r($value, true)."</pre>";
            //print $this->getName().": ".$value->getName()." => ".floatval($value[0])." ".(($value->attributes()=="cm") ? "cm" : "mm")."<br/>";
            $v = \floatval($value[0]);
            if ($v > 0.0)
            {
                $ret[] = ($value->attributes()=="cm") ? $v : $v/10;
            }
        }
        return $ret;
    }
}
