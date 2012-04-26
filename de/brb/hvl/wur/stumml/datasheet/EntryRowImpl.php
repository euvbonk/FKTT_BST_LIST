<?php

import('de_brb_hvl_wur_stumml_datasheet_EntryRow');

class EntryRowImpl implements EntryRow
{
    private $name;
    private $url;
    private $short;
    private $type;
    private $lastChange;
    
    public function __construct($name, $url, $short, $type, $lastChange)
    {
        $this->name = $name;
        $this->url = $url;
        $this->short = $short;
        $this->type = $type;
        $this->lastChange = $lastChange;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName(String $n)
    {
        $this->name = $n;
    }
    
    public function getShort()
    {
        return $this->short;
    }
    
    public function setShort(String $s)
    {
        $this->short = $s;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function setType(String $t)
    {
        $this->type = $t;
    }
    
    public function getLastChange()
    {
        return $this->lastChange;
    }
    
    public function setLastChange(String $l)
    {
        $this->lastChange = $l;
    }
    
    public function setSheetUrl(String $u)
    {
        $this->url = $u;
    }
    
    public function getSheetUrl()
    {
        return $this->url;
    }
}
?>
