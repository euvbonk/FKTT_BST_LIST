<?php

interface GoodsTrafficListRowData
{
    /**
     * @return DatasheetElement
     */
    public function getDatasheetElement();

    /**
     * @return File
     */
    public function getFile();
}