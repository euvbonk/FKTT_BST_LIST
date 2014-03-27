<?php
namespace org\fktt\bstlist\beans\tableList\datasheet;

\import('de_brb_hvl_wur_stumml_beans_datasheet_xml_BaseElement');
\import('de_brb_hvl_wur_stumml_beans_tableList_datasheet_DatasheetListRowData');
\import('de_brb_hvl_wur_stumml_io_File');

use org\fktt\bstlist\beans\datasheet\xml\BaseElement;
use org\fktt\bstlist\io\File;

class DatasheetListRowDataImpl implements DatasheetListRowData
{
    private $oIndex;
    private $oBaseElement;
    private $oFile;
    private $isEditorPresent;

    public function __construct($index, BaseElement $xml, File $file, $isEditorPresent)
    {
        $this->oIndex = $index;
        $this->oBaseElement = $xml;
        $this->oFile = $file;
        $this->isEditorPresent = $isEditorPresent;
    }

    /**
     * @return integer
     */
    public function getIndex()
    {
        return $this->oIndex;
    }

    /**
     * @return BaseElement
     */
    public function getBaseElement()
    {
        return $this->oBaseElement;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->oFile;
    }

    /**
     * @return bool
     */
    public function isEditorPresent()
    {
        return $this->isEditorPresent;
    }
}