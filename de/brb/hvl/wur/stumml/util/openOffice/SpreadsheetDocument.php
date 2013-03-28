<?php

import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_util_openOffice_OpenDocument');
import('de_brb_hvl_wur_stumml_util_Point');
import('de_brb_hvl_wur_stumml_util_openOffice_XSpreadsheet');

class SpreadsheetDocument extends OpenDocument
{
    private $oFile = null;
    private $oCellPosition = null;
    private $oXSpreadsheet = null;

    /**
     * @return SpreadsheetDocument
     */
    public function __construct()
    {
        parent::__construct();
        $this->oCellPosition = new Point(0, 0);
        return $this;
    }

    /**
     * @param File $file
     */
    public function openDocumentFromFile(File $file)
    {
        $this->setDocument($this->loadDocument($file));
        $this->setDocumentFile($file);
        $this->setSpreadsheetDocument();
    }

    /**
     * @throws Exception
     */
    public function saveDocument()
    {
        if ($this->getDocumentFile() != null)
        {
            $this->saveDocumentToFile($this->getDocumentFile());
        }
        else
        {
            throw new Exception("Document could not be saved, no file name was set!");
        }
    }

    /**
     * @param File $file
     */
    public function setDocumentFile(File $file)
    {
        $this->oFile = $file;
    }

    /**
     * @return File|null
     */
    public function getDocumentFile()
    {
        return $this->oFile;
    }

    /*@Override*/
    public function closeDocument()
    {
        $this->oFile = null;
        $this->oCellPosition = null;
        $this->oXSpreadsheet = null;
        parent::closeDocument();
    }

    /**
     * @param int $column
     * @param int $row
     */
    public function setCellPositionByIndex($column, $row)
    {
        $this->oCellPosition->setLocation($column, $row);
    }

    /**
     * @return Point
     */
    public function getCurrentCellPosition()
    {
        return $this->oCellPosition->getLocation();
    }

    /**
     * @param string $cellText
     */
    public function setTextToCurrentCell($cellText)
    {
        $cellLocation = $this->getCurrentCellPosition();
        $cell = $this->getSpreadsheetDocument()->getCellByPosition($cellLocation->x, $cellLocation->y);
        $cell->setFormula($cellText);
    }

    /**
     * @param string $cellText
     * @param int $xIndex
     * @param int $yIndex
     */
    public function setTextAtCellPositionByIndex($cellText, $xIndex, $yIndex)
    {
        $this->setCellPositionByIndex($xIndex, $yIndex);
        $this->setTextToCurrentCell($cellText);
    }

    /**
     * @return XSpreadsheet
     */
    protected function getSpreadsheetDocument()
    {
        return $this->oXSpreadsheet;
    }

    public function setSpreadsheetDocument()
    {
        // den Dateiinhalt in ein SimpleXMLElement Object umwandeln
        $SXE = $this->getDocument();
        // die Namespaces benötigen wir später, um auf die einzelnen
        // Kindelemente zugreifen oder sie neu anlegen zu können
        // $ns ist vom Typ Array
        $ns = $SXE->getDocNamespaces(true);
        // zunächst holen wir alle Kindelemente, die den NameSpace
        // "office" haben, vergleiche dazu den Aufbau der "content.xml"
        $office = $SXE->children($ns['office']);
        // $office enthält alle Kindelemente als SimpleXMLElement Objekt
        // dies sind: document-content, scripts, font-face-decls,
        //            automatic-styles, body, spreadsheet

        // mit dem nun folgenden X-Path Statement greifen wir von der
        // Wurzel des Dokuments auf den Spreadsheet Teil zu.
        // xpath liefert dabei ein array mit SimpleXMLElement Objekten für
        // jeden gefundenen Knoten, was in unserem Falle allen vorhandenen
        // Tabellen entspricht
        $spreadsheet = $office->xpath("//office:body/office:spreadsheet");
        
        // Ich gehe davon aus, dass man entweder die Vorlage benutzt, die
        // nur eine Tabelle enthält, oder ein leeres Dokument verwendet,
        // welches drei leere Tabellen enthält. In beiden Fällen benutzen
        // wir jeweils die erste vorkommende Tabelle
        $this->oXSpreadsheet = new XSpreadsheet($spreadsheet[0]->children($ns['table']), $ns);
    }
}
