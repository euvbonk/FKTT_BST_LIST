<?php
namespace org\fktt\bstlist\beans\tableList\datasheet;

\import('beans_tableList_datasheet_AbstractDatasheetList');
\import('beans_tableList_datasheet_DatasheetListRowData');
\import('html_util_HtmlUtil');
\import('util_QI');
\import('util_reportTable_ListRowCells');

use org\fktt\bstlist\html\util\HtmlUtil;
use org\fktt\bstlist\io\File;
use org\fktt\bstlist\util\QI;
use org\fktt\bstlist\util\reportTable\ListRowCells;

class StationDatasheetList extends AbstractDatasheetList
{
    /**
     * @param string $order [optional]
     * @return StationDatasheetList
     */
    public function __construct($order = "ORDER_SHORT")
    {
        parent::__construct($order);

        return $this;
    }

    /**
     * @param DatasheetListRowData $data
     * @param array $lang
     * @return ListRowCells
     */
    protected function getListRowImpl(DatasheetListRowData $data, array $lang = null)
    {
        return new SheetListRowEntries($data, $lang);
    }
}

class SheetListRowEntries implements ListRowCells
{
    private $oListRowData;
    private $oLang;

    public function __construct(DatasheetListRowData $data, array $lang)
    {
        $this->oListRowData = $data;
        $this->oLang = $lang;
    }

    /**
     * @return DatasheetListRowData
     */
    protected function getData()
    {
        return $this->oListRowData;
    }

    /**
     * @return array mixed
     */
    public function getCellsContent()
    {
        $index = (($this->getData()->getIndex() > 9) ? $this->getData()->getIndex() : "0".$this->getData()->getIndex()).".";
        $name = $this->getData()->getBaseElement()->getValueForTag('name');
        $datei = $this->getData()->getFile();
        $nameRef = $datei->toDownloadLink($name, false);
        $kuerzel = $this->getData()->getBaseElement()->getValueForTag('kuerzel');
        $kuerzelRef = $this->getLangSelect($kuerzel, $datei);
        $type = $this->getData()->getBaseElement()->getValueForTag('typ');
        $lastChange = \strftime("%a, %d. %b %Y %H:%M", $datei->getMTime());
        return array($index, $nameRef, $kuerzelRef, HtmlUtil::toUtf8($type), $lastChange,
            $this->buildCommandLink("Fpl_View", $kuerzel, $datei));
    }

    /**
     * @return array string
     */
    public function getCellsStyle()
    {
        return array("style=\"text-align:center;\"", "", "style=\"text-align:center;\"", "style=\"text-align:center;\"",
            "", "style=\"text-align:center;\"");
    }

    protected function buildCommandLink($pageName, $label, File $url)
    {
        return HtmlUtil::toUtf8(QI::buildAbsoluteLink($pageName, $label,
                "cmd=".$url->getParentFile()->getName()."-".$url->getBasename(".xml")));
    }

    protected function getLangSelect($kuerzel, File $url)
    {
        $ret  = "<select size=\"1\" class=\"datasheet-lang-select\">";
        $ret .= "<option value='#'>{$kuerzel}</option>";
        foreach ($this->oLang as $lang)
        {
            $ck = $url->getParentFile()->getName()."-".$url->getBasename(".xml");
            $ret .= "<option value='cmd={$ck}&amp;lang={$lang}'>{$kuerzel} ({$lang})</option>";
        }
        $ret .= "</select>";
        return $ret;
    }
}
