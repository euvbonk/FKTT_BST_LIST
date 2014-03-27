<?php
namespace org\fktt\bstlist\beans\yellowpage;

\import('de_brb_hvl_wur_stumml_util_openOffice_SpreadsheetXml');

use org\fktt\bstlist\util\openOffice\SpreadsheetXml;

class YellowPageTableRowCell implements SpreadsheetXml
{
    private $oContent = null;

    /**
     * @param string $content [optional]
     * @return YellowPageTableRowCell
     */
    public function __construct($content = "")
    {
        $this->setContent($content);
        return $this;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->oContent = $content;
    }

    /**
     * @return null|string
     */
    public function getContent()
    {
        return $this->oContent;
    }

    /**
     * @return string
     */
    public function getAsSpreadsheetXml()
    {
        return (\strlen($this->getContent()) > 0) ? "<table:table-cell office:value-type=\"string\"><text:p>".$this->getContent()."</text:p></table:table-cell>" : "<table:table-cell/>";
    }
}
