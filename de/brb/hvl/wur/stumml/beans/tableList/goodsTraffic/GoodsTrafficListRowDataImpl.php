<?php
namespace org\fktt\bstlist\beans\datasheet\tableList\goodsTraffic;

\import('de_brb_hvl_wur_stumml_beans_datasheet_xml_DatasheetElement');
\import('de_brb_hvl_wur_stumml_beans_tableList_goodsTraffic_GoodsTrafficListRowData');
\import('de_brb_hvl_wur_stumml_io_File');
use org\fktt\bstlist\beans\datasheet\xml\DatasheetElement;
use org\fktt\bstlist\io\File;

class GoodsTrafficListRowDataImpl implements GoodsTrafficListRowData
{
    private $oDatasheetElement;
    private $oFile;

    public function __construct(DatasheetElement $element, File $file)
    {
        $this->oDatasheetElement = $element;
        $this->oFile = $file;
    }

    /**
     * @return DatasheetElement
     */
    public function getDatasheetElement()
    {
        return $this->oDatasheetElement;
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->oFile;
    }
}