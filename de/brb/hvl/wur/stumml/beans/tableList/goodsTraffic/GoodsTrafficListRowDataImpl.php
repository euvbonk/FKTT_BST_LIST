<?php

import('de_brb_hvl_wur_stumml_beans_tableList_goodsTraffic_GoodsTrafficListRowData');

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