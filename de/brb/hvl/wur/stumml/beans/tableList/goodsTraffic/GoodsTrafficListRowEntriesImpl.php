<?php

import('de_brb_hvl_wur_stumml_beans_tableList_AbstractListRowEntriesImpl');
import('de_brb_hvl_wur_stumml_beans_tableList_goodsTraffic_GoodsTrafficListRowEntries');
import('de_brb_hvl_wur_stumml_util_reportTable_ReportTableList');

class GoodsTrafficListRowEntriesImpl extends AbstractListRowEntriesImpl implements GoodsTrafficListRowEntries
{
    private $identifier;
    private $input;
    private $output;
    private $maxInOut;
    private $shortest;
    private $longest;
    
    public function __construct($name, $short, $url, $ident, $input, $output, $maxInOut, $st, $lt)
    {
        parent::__construct($name, $short, $url);
        $this->setIdentifier($ident);
        $this->setInput($input);
        $this->setOutput($output);
        $this->setMaxInOutput($maxInOut);
        $this->setShortestTrack($st);
        $this->setLongestTrack($lt);
    }
    
    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($i)
    {
        $this->identifier = $i;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getMaxInOutput()
    {
        return $this->maxInOut;
    }

    public function getShortestTrack()
    {
        return $this->shortest;
    }

    public function getLongestTrack()
    {
        return $this->longest;
    }

    public function setInput($t)
    {
        $this->input = $t;
    }
    
    public function setOutput($t)
    {
        $this->output = $t;
    }
    
    public function setMaxInOutput($t)
    {
        $this->maxInOut = $t;
    }
    
    public function setShortestTrack($l)
    {
        $this->shortest = $l;
    }
    
    public function setLongestTrack($i)
    {
        $this->longest = $i;
    }

    public function getCellsContent()
    {
        return array(
                     HtmlUtil::toUtf8($this->getIdentifier()),
                     $this->getShortWithReference(),
                     $this->getNameWithReference(),
                     sprintf(ReportTableListProperties::FORMAT, $this->getInput()),
                     sprintf(ReportTableListProperties::FORMAT, $this->getOutput()),
                     sprintf(ReportTableListProperties::FORMAT, $this->getMaxInOutput()),
                     $this->getShortestTrack(),
                     $this->getLongestTrack()
                    );
    }

    public function getCellsStyle()
    {
        $s = "style=\"text-align:center;\"";
        return array("", $s, "", $s, $s, $s, $s, $s);
    }
}
?>
