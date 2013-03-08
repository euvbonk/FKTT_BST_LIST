<?php

import('de_brb_hvl_wur_stumml_beans_tableList_AbstractListRowEntries');
import('de_brb_hvl_wur_stumml_Settings');

abstract class AbstractListRowEntriesImpl implements AbstractListRowEntries
{
    private $name;
    private $short;
    private $url;

    public function __construct($name, $short, $url)
    {
        $this->setName($name);
        $this->setShort($short);
        $this->setSheetUrl($url);
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($n)
    {
        $this->name = $n;
    }
    
    public function getShort()
    {
        return $this->short;
    }
    
    public function setShort($s)
    {
        $this->short = $s;
    }

    public function setSheetUrl($u)
    {
        $this->url = $u;
    }
    
    public function getSheetUrl()
    {
        return $this->url;
    }

    public function getNameWithReference()
    {
        return Settings::getDownloadLinkForFile($this->getSheetUrl(), $this->getName(), false);
    }

    public function getShortWithReference()
    {
        return Settings::getDownloadLinkForFile($this->getSheetUrl(), $this->getShort(), false);
    }
}
?>
