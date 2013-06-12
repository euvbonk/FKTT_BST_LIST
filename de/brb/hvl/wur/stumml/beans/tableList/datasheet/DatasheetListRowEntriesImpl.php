<?php

import('de_brb_hvl_wur_stumml_beans_tableList_AbstractListRowEntriesImpl');
import('de_brb_hvl_wur_stumml_beans_tableList_datasheet_DatasheetListRowEntries');
import('de_brb_hvl_wur_stumml_html_util_HtmlUtil');
import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_util_QI');

class DatasheetListRowEntriesImpl extends AbstractListRowEntriesImpl implements DatasheetListRowEntries
{
    private $index;
    private $type;
    private $lastChange;

    /**
     * @param string $name
     * @param string $short
     * @param int    $index
     * @param File $url
     * @param string $type
     * @param string $lastChange
     * @return DatasheetListRowEntriesImpl
     */
    public function __construct($name, $short, $index, File $url, $type, $lastChange)
    {
        parent::__construct($name, $short, $url);
        $this->setIndex($index);
        $this->setType($type);
        $this->setLastChange($lastChange);
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $t
     */
    public function setType($t)
    {
        $this->type = $t;
    }

    /**
     * @return string
     */
    public function getLastChange()
    {
        return $this->lastChange;
    }

    /**
     * @param string $l
     */
    public function setLastChange($l)
    {
        $this->lastChange = $l;
    }

    /**
     * @param int $i
     */
    public function setIndex($i)
    {
        $this->index = $i;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return array mixed
     */
    public function getCellsContent()
    {
        return array((($this->getIndex() > 9) ? $this->getIndex() : "0".$this->getIndex()).".",
            $this->getNameWithReference(), $this->getShortWithReference(), HtmlUtil::toUtf8($this->getType()),
            $this->getLastChange(), $this->buildCommandLink("Fpl_View", $this->getShort(), $this->getSheetUrl()),
            $this->buildCommandLink("Edit_Datasheet",
                "<img src=\"http://www.java.com/js/webstart.png\"  alt=\"Java WS Launch Button\"/>",
                $this->getSheetUrl()));
    }

    /**
     * @return array string
     */
    public function getCellsStyle()
    {
        return array("style=\"text-align:center;\"", "", "style=\"text-align:center;\"", "style=\"text-align:center;\"",
            "", "style=\"text-align:center;\"", "style=\"text-align:center;\"");
    }

    protected function buildCommandLink($pageName, $label, $url)
    {
        return HtmlUtil::toUtf8(QI::buildAbsoluteLink($pageName, $label,
                "cmd=".str_replace(".xml", "", basename($url))));
    }
}
