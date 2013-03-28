<?php

import('de_brb_hvl_wur_stumml_beans_tableList_AbstractListRowEntries');
import('de_brb_hvl_wur_stumml_Settings');

abstract class AbstractListRowEntriesImpl implements AbstractListRowEntries
{
    private $name;
    private $short;
    private $url;

    /**
     * @param string $name
     * @param string $short
     * @param string $url
     * @return AbstractListRowEntriesImpl
     */
    public function __construct($name, $short, $url)
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
     * @param string $u
     */
    public function setSheetUrl($u)
    {
        $this->url = $u;
    }

    /**
     * @return string
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
        return Settings::getDownloadLinkForFile($this->getSheetUrl(), $this->getName(), false);
    }

    /**
     * @return string
     */
    public function getShortWithReference()
    {
        return Settings::getDownloadLinkForFile($this->getSheetUrl(), $this->getShort(), false);
    }
}
