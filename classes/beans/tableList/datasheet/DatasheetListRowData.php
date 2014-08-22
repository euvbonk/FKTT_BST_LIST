<?php
namespace org\fktt\bstlist\beans\tableList\datasheet;

use org\fktt\bstlist\beans\datasheet\xml\BaseElement;
use org\fktt\bstlist\io\File;

interface DatasheetListRowData
{
    /**
     * @return integer
     */
    function getIndex();

    /**
     * @return BaseElement
     */
    function getBaseElement();

    /**
     * @return File
     */
    function getFile();
}