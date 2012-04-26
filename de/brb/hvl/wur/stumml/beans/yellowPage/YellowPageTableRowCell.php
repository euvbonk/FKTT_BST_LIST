<?php

interface OpenOfficeTableXml
{
    public function getAsOpenOfficeFormat();
}

class YellowPageTableRowCell implements OpenOfficeTableXml
{
    private $oContent = null;
    
    public function __construct($content = "")
    {
        $this->setContent($content);
    }

    public function setContent($content)
    {
        $this->oContent = $content;
    }
    
    public function getContent()
    {
        return $this->oContent;
    }

    public function getAsOpenOfficeFormat()
    {
        return (strlen($this->getContent()) > 0) ? "<table:table-cell office:value-type=\"string\"><text:p>".$this->getContent()."</text:p></table:table-cell>" : "<table:table-cell/>";
    }
}
?>
