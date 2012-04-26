<?php

import('de_brb_hvl_wur_stumml_util_openOffice_SpreadsheetXml');
import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageTableRowCellList');

class YellowPageTableRowList extends ArrayObject implements SpreadsheetXml
{
    public function append(YellowPageTableRowCellList $cell)
    {
        parent::append($cell);
    }

    public function addRow(YellowPageTableRowCellList $cell)
    {
        $this->append($cell);
    }

    public function getAsSpreadsheetXml()
    {
        $str = "";
        if ($this->count() > 0)
        {
            $index = 0;
            foreach ($this->getIterator() as $row)
            {
                $str .= $row->getAsSpreadsheetXml();
                $index++;
            }
            $str .= '<table:table-row table:style-name="ro1" table:number-rows-repeated="'.(65534-$index);
        }
        return $str;
    }
}
?>
