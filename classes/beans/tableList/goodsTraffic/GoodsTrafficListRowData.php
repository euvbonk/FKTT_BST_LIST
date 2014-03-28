<?php
namespace org\fktt\bstlist\beans\datasheet\tableList\goodsTraffic;

use org\fktt\bstlist\beans\datasheet\xml\DatasheetElement;
use org\fktt\bstlist\io\File;

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