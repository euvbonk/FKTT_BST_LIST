<?php
namespace org\fktt\bstlist\util\openOffice;

use SimpleXMLElement;

/* represents a spreadsheet cell */
class XCell
{
    private /*Array([string] => string,...)*/ $NameSpaces = null;
    private /*SimpleXMLElement*/ $oXml = null;

    /**
     * @param SimpleXMLElement $content
     * @param array string     $namespaces
     * @return XCell
     */
    public function __construct(SimpleXMLElement $content, $namespaces)
    {
        $this->oXml = $content;
        $this->NameSpaces = $namespaces;
        return $this;
    }

    /**
     * @return SimpleXMLElement
     */
    protected function getXml()
    {
        return $this->oXml;
    }

    /**
     * @param string $text
     */
    public function setFormula($text)
    {
        if (\strlen($text) > 0)
        {
            $this->getXml()->addAttribute("office:value-type", "string", $this->NameSpaces['office']);
            $this->getXml()->addChild("text:p", $text, $this->NameSpaces['text']);
        }
    }
}