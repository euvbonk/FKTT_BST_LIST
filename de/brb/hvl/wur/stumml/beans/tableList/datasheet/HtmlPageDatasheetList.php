<?php

import('de_brb_hvl_wur_stumml_beans_tableList_datasheet_AbstractDatasheetList');
import('de_brb_hvl_wur_stumml_beans_tableList_datasheet_DatasheetListRowData');

import('de_brb_hvl_wur_stumml_util_reportTable_ListRowCells');

class HtmlPageDatasheetList extends AbstractDatasheetList
{
    /**
     * @param bool   $isEditorPresent
     * @param string $order [optional]
     * @return HtmlPageDatasheetList
     */
    public function __construct($isEditorPresent, $order = "ORDER_SHORT")
    {
        parent::__construct($isEditorPresent, $order);

        return $this;
    }

    /**
     * @param DatasheetListRowData $data
     * @param array $lang
     * @return ListRowCells
     */
    protected function getListRowImpl(DatasheetListRowData $data, array $lang = null)
    {
        return new HtmlPageListRowEntries($data);
    }
}

class HtmlPageListRowEntries implements ListRowCells
{
    private $oListRowData;

    public function __construct(DatasheetListRowData $data)
    {
        $this->oListRowData = $data;
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
        $Html = $this->buildLink($datei, ".html", $kuerzel);
        $Fpl = $this->buildLink($datei, "_fpl.html", $kuerzel);
        //$Pdf = $this->buildLink($datei, ".pdf", $kuerzel); spätere PDF Ansicht

        $type = $this->getData()->getBaseElement()->getValueForTag('typ');
        $lastChange = strftime("%a, %d. %b %Y %H:%M", $datei->getMTime());
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
        if ($newSuffix != null && strlen($newSuffix) > 0)
        {
            $base = $file->getParent()."/".$file->getBasename(".xml");
            $newFile = new File($base.$newSuffix);
        }
        else
        {
            $newFile = $file;
        }
        $link = $newFile->toDownloadLink($label, false);
        $uri = $newFile->toHttpUrl();
        return str_replace($uri, "./".$newFile->getParentFile()->getName()."/".$newFile->getBasename(), $link);
    }
}
