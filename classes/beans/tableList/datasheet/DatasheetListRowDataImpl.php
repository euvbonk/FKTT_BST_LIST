<?php
namespace org\fktt\bstlist\beans\tableList\datasheet;

\import('beans_datasheet_xml_BaseElement');
\import('beans_tableList_datasheet_DatasheetListRowData');
\import('io_File');

use org\fktt\bstlist\beans\datasheet\xml\BaseElement;
use org\fktt\bstlist\io\File;

class DatasheetListRowDataImpl implements DatasheetListRowData
{
    private $oIndex;
    private $oBaseElement;
    private $oFile;

    public function __construct($index, BaseElement $xml, File $file)
    {
        $this->oIndex = $index;
        $this->oBaseElement = $xml;
        $this->oFile = $file;
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
}