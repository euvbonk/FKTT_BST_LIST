<?php
namespace org\fktt\bstlist\beans\tableList\datasheet;

\import('beans_tableList_datasheet_AbstractDatasheetList');
\import('beans_tableList_datasheet_DatasheetListRowData');
\import('util_reportTable_ListRowCells');

use org\fktt\bstlist\html\util\HtmlUtil;
use org\fktt\bstlist\io\File;
use org\fktt\bstlist\util\reportTable\ListRowCells;

class HtmlPageDatasheetList extends AbstractDatasheetList
{
    private $oXslFiles;
    /**
     * @param array $xslFiles
     * @return HtmlPageDatasheetList
     */
    public function __construct(array $xslFiles)
    {
        parent::__construct(false);
        $this->oXslFiles = $xslFiles;
        return $this;
    }

    /**
     * @param DatasheetListRowData $data
     * @param array $lang
     * @return ListRowCells
     */
    protected function getListRowImpl(DatasheetListRowData $data, array $lang = null)
    {
        return new HtmlPageListRowEntries($data, $this->oXslFiles);
    }
}

class HtmlPageListRowEntries implements ListRowCells
{
    private $oListRowData;
    private $oXslFiles;

    public function __construct(DatasheetListRowData $data, array $xslFiles)
    {
        $this->oListRowData = $data;
        $this->oXslFiles = $xslFiles;
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
        $kuerzel = $this->getData()->getBaseElement()->getValueForTag('kuerzel');

        $Xml = $this->buildLink($datei, null, $kuerzel);
        $Html = $this->buildSelect($datei, $kuerzel);
        $Fpl = $this->buildLink($datei, "_fpl.html", $kuerzel);
        //$Pdf = $this->buildLink($datei, ".pdf", $kuerzel); spätere PDF Ansicht

        $type = $this->getData()->getBaseElement()->getValueForTag('typ');
        $lastChange = \strftime("%a, %d. %b %Y %H:%M", $datei->getMTime());
        return array($index, $name, $kuerzel, HtmlUtil::toUtf8($type), $lastChange, $Xml, $Html, $Fpl/*, $Pdf fuer spaetere PDF Ansicht */
        );
    }

    /**
     * @return array string
     */
    public function getCellsStyle()
    {
        return array("style=\"text-align:center;\"",
                     "",
                     "style=\"text-align:center;\"",
                     "style=\"text-align:center;\"",
                     "",
                     "style=\"text-align:center;\"",
                     "style=\"text-align:center;\"",
                     /*"style=\"text-align:center;\"", fuer spaetere PDF Ansicht */
                     "style=\"text-align:center;\"");
    }

    protected function buildLink(File $file, $newSuffix, $label)
    {
        if ($newSuffix != null && \strlen($newSuffix) > 0)
        {
            $base = $file->getParent()."/".$file->getBasename(".xml");
            $newFile = new File($base.$newSuffix);
        }
        else
        {
            $newFile = $file;
        }
        $uri = $newFile->toHttpUrl();
        if ($label != null && \strlen($label) > 0)
        {
            $subject = $newFile->toDownloadLink($label, false, false);
        }
        else
        {
            $subject = $uri;
        }
        return \str_replace($uri, "./".$newFile->getParentFile()->getName()."/".$newFile->getBasename(), $subject);
    }

    protected function buildSelect(File $datei, $kuerzel)
    {
        $ret = "<select size='1' onChange='run(this);'>";
        $ret .= "<option value='#'>{$kuerzel}</option>";
        foreach ($this->oXslFiles as $key => $value)
        {
            $s = "";
            $newSuffix = ".html";
            if (\strlen($key) == 0) //DE
            {
                $s = " (DE)";
            }
            else if ($key != "_fpl")
            {
                $s = " (".\strtoupper(\substr($key, 1)).")";
                $newSuffix = $key.$newSuffix;
            }
            if ($s != "")
            {
                $url = $this->buildLink($datei, $newSuffix, null);
                $ret .= "<option value='{$url}'>{$kuerzel}{$s}</option>";
            }
        }
        $ret .= "</select>";
        return $ret;
    }
}
