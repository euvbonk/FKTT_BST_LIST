<?php

import('de_brb_hvl_wur_stumml_util_openOffice_SpreadsheetXml');

class YellowPageTableRowCell implements SpreadsheetXml
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

    public function getAsSpreadsheetXml()
    {
        return (strlen($this->getContent()) > 0) ? "<table:table-cell office:value-type=\"string\"><text:p>".$this->getContent()."</text:p></table:table-cell>" : "<table:table-cell/>";
    }
}
?>
