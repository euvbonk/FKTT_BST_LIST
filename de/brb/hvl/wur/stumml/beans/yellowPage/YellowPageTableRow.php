<?php

import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageTableRowCell');

class YellowPageTableRow implements OpenOfficeTableXml
{
    private $oList = null;
    
    public function __construct()
    {
        $this->oList = array();
    }

    public function addCell(YellowPageTableRowCell $cell)
    {
        $this->oList[] = $cell;
    }
    
    public function getYellowPageTableRowCells()
    {
        return $this->oList;
    }

    public function getAsOpenOfficeFormat()
    {
        $str = "<table:table-row table:style-name=\"ro1\">";
        if (count($this->getYellowPageTableRowCells()) > 0)
        {
            foreach ($this->getYellowPageTableRowCells() as $cell)
            {
                $str .= $cell->getAsOpenOfficeFormat();
            }
            $str .= "<table:table-cell table:number-columns-repeated=\"1017\"/>";
        }
        else
        {
            $str .= "<table:table-cell table:number-columns-repeated=\"1024\"/>";
        }
        $str .= "</table:table-row>";
        return $str;
    }
}
?>
