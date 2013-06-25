<?php

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

    /**
     * @return bool
     */
    function isEditorPresent();
}