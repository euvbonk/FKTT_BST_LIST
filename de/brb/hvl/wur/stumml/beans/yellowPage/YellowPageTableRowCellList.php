<?php

import('de_brb_hvl_wur_stumml_util_openOffice_SpreadsheetXml');
import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageTableRowCell');

class YellowPageTableRowCellList extends ArrayObject implements SpreadsheetXml
{
    public function append(YellowPageTableRowCell $cell)
    {
        parent::append($cell);
    }

    public function addCell(YellowPageTableRowCell $cell)
    {
        $this->append($cell);
    }

    public function getAsSpreadsheetXml()
    {
        $str = "<table:table-row table:style-name=\"ro1\">";
        if ($this->count() > 0)
        {
            foreach ($this->getIterator() as $cell)
            {
                $str .= $cell->getAsSpreadsheetXml();
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
