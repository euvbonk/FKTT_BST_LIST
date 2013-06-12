<?php

import('de_brb_hvl_wur_stumml_beans_tableList_AbstractListRowEntries');
import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_io_File');

abstract class AbstractListRowEntriesImpl implements AbstractListRowEntries
{
    private $name;
    private $short;
    private $url;

    /**
     * @param string $name
     * @param string $short
     * @param File $url
     * @return AbstractListRowEntriesImpl
     */
    public function __construct($name, $short, File $url)
    {
        $this->setName($name);
        $this->setShort($short);
        $this->setSheetUrl($url);
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $n
     */
    public function setName($n)
    {
        $this->name = $n;
    }

    /**
     * @return string
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * @param string $s
     */
    public function setShort($s)
    {
        $this->short = $s;
    }

    /**
     * @param File $u
     */
    public function setSheetUrl(File $u)
    {
        $this->url = $u;
    }

    /**
     * @return File
     */
    public function getSheetUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getNameWithReference()
    {
        return $this->getSheetUrl()->toDownloadLink($this->getName(), false);
    }

    /**
     * @return string
     */
    public function getShortWithReference()
    {
        return $this->getSheetUrl()->toDownloadLink($this->getShort(), false);
    }
}
