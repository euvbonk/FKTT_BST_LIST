<?php
class DatasheetElement
{
    private $oXml = null;
    private $daysAWeek = 7;
    private $oMainTracks = array();

    public function __construct($filename, $days = 7)
    {
        $this->oXml = simplexml_load_file($filename);
        $this->daysAWeek = $days;
        $this->oMainTracks = $this->getAllLengthInCm($this->oXml->xpath("gleise/hgleise/gleis/laenge"));
    }

    public function getShort()
    {
        return (string)$this->oXml->kuerzel;
    }

    public function getName()
    {
        return (string)$this->oXml->name;
    }

    /**
     * Also have a look at the bahnhof.xsl, where this code is adapted from!
     */
    public function getCarsInput()
    {
        $f = $this->summe($this->oXml->xpath("gv/verlader/empfang/ladegut/wagen[@zeitraum='tag']"));
        $g = $this->summe($this->oXml->xpath("gv/verlader/empfang/ladegut/wagen"));
        $h = $this->summe($this->oXml->xpath("gv/verlader/empfang/ladegut/wagen[@zeitraum='tag']"));
        $s = $f + ($g-$h)/$this->daysAWeek;
        //print $this->getName().": ".$f." + (".$g." - ".$h.") / ".$this->daysAWeek." = ".$s."<br/>";
        return $s;
    }

    /**
     * Also have a look at the bahnhof.xsl, where this code is adapted from!
     */
    public function getCarsOutput()
    {
        $f = $this->summe($this->oXml->xpath("gv/verlader/versand/ladegut/wagen[@zeitraum='tag']"));
        $g = $this->summe($this->oXml->xpath("gv/verlader/versand/ladegut/wagen"));
        $h = $this->summe($this->oXml->xpath("gv/verlader/versand/ladegut/wagen[@zeitraum='tag']"));
        $s = $f + ($g-$h)/$this->daysAWeek;
        //print $this->getName().": ".$f." + (".$g." - ".$h.") / ".$this->daysAWeek." = ".$s."<br/>";
        return $s;
    }

    public function getCarsMax()
    {
        return max($this->getCarsInput(), $this->getCarsOutput());
    }

    public function getLongestMainTrackLength()
    {
        return (!empty($this->oMainTracks)) ? max($this->oMainTracks) : "kA";
    }
    
    public function getShortestMainTrackLength()
    {
        return (!empty($this->oMainTracks)) ? min($this->oMainTracks) : "kA";
    }
    
    private function summe($array)
    {
        $ret = 0.0;
        //print "<pre>".print_r($array, true)."</pre>";
        if (!empty($array))
        {
            foreach($array as $key => $value)
            {
                $ret += floatval($value);
            }
        }
        return $ret;
    }
    
    private function getAllLengthInCm($array)
    {
        $ret = array();
        foreach ($array as $key => $value)
        {
            //print $this->getName().": ".$key." => ".floatval($value)." ".(($value->attributes()=="cm") ? "cm" : "mm")."<br/>";
            $v = floatval($value);
            if ($v > 0.0)
            {
                $ret[] = ($value->attributes()=="cm") ? $v : $v/10;
            }
        }
        return $ret;
    }
}
?>
