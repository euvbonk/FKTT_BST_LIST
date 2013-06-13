<?php

import('de_brb_hvl_wur_stumml_beans_tableList_AbstractListRowEntriesImpl');
import('de_brb_hvl_wur_stumml_beans_tableList_goodsTraffic_GoodsTrafficListRowEntries');
import('de_brb_hvl_wur_stumml_html_util_HtmlUtil');
import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_util_reportTable_ReportTableList');

class GoodsTrafficListRowEntriesImpl extends AbstractListRowEntriesImpl implements GoodsTrafficListRowEntries
{
    private $identifier;
    private $input;
    private $output;
    private $maxInOut;
    private $shortest;
    private $longest;

    /**
     * @param string $name
     * @param string $short
     * @param File $url
     * @param string $ident
     * @param float  $input
     * @param float  $output
     * @param float  $maxInOut
     * @param float|string $st
     * @param float|string $lt
     * @return GoodsTrafficListRowEntriesImpl
     */
    public function __construct($name, $short, File $url, $ident, $input, $output, $maxInOut, $st, $lt)
    {
        parent::__construct($name, $short, $url);
        $this->setIdentifier($ident);
        $this->setInput($input);
        $this->setOutput($output);
        $this->setMaxInOutput($maxInOut);
        $this->setShortestTrack($st);
        $this->setLongestTrack($lt);
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $i
     */
    public function setIdentifier($i)
    {
        $this->identifier = $i;
    }

    /**
     * @return float
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return float
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return float
     */
    public function getMaxInOutput()
    {
        return $this->maxInOut;
    }

    /**
     * @return float|string
     */
    public function getShortestTrack()
    {
        return $this->shortest;
    }

    /**
     * @return float|string
     */
    public function getLongestTrack()
    {
        return $this->longest;
    }

    /**
     * @param float $t
     */
    public function setInput($t)
    {
        $this->input = $t;
    }

    /**
     * @param float $t
     */
    public function setOutput($t)
    {
        $this->output = $t;
    }

    /**
     * @param float $t
     */
    public function setMaxInOutput($t)
    {
        $this->maxInOut = $t;
    }

    /**
     * @param float|string $l
     */
    public function setShortestTrack($l)
    {
        $this->shortest = $l;
    }

    /**
     * @param float|string $i
     */
    public function setLongestTrack($i)
    {
        $this->longest = $i;
    }

    /**
     * @return array mixed
     */
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

    /**
     * @return array string
     */
    public function getCellsStyle()
    {
        $s = "style=\"text-align:center;\"";
        return array("", $s, "", $s, $s, $s, $s, $s);
    }
}
