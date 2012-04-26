<?php

/* represents a spreadsheet cell */
class XCell
{
    private /*Array([string] => string,...)*/ $NameSpaces = null;
    private /*SimpleXMLElement*/ $oXml = null;

    public function __construct($content, $namespaces)
    {
        $this->oXml = $content;
        $this->NameSpaces = $namespaces;
    }
    
    public function setFormula($text)
    {
        if (strlen($text)>0)
        {
            $this->oXml->addAttribute("office:value-type", "string", $this->NameSpaces['office']);
            $this->oXml->addChild("text:p", $text, $this->NameSpaces['text']);
        }
    }
}
?>
