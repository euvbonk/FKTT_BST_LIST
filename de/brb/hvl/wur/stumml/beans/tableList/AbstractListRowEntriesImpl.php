<?php

import('de_brb_hvl_wur_stumml_beans_tableList_AbstractListRowEntries');
import('de_brb_hvl_wur_stumml_html_util_HtmlUtil');

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

    protected function buildRelativePath($path)
    {
        global $rootDir;
        return substr($path, strlen($rootDir)+1);
    }

    public function setSheetUrl($u)
    {
        $this->url = $u;
    }
    
    public function getSheetUrl()
    {
        return $this->url;
    }

    protected function getAbsoluteLink($url, $label)
    {
        return str_replace('index.php/', '', HtmlUtil::toUtf8(common::AbsoluteLink($url, $label)));
    }

    public function getNameWithReference()
    {
        return $this->getAbsoluteLink($this->buildRelativePath($this->getSheetUrl()), $this->getName());
    }

    public function getShortWithReference()
    {
        return $this->getAbsoluteLink($this->buildRelativePath($this->getSheetUrl()), $this->getShort());
    }
}
?>
