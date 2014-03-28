<?php
namespace org\fktt\bstlist\beans\yellowpage;

\import('beans_yellowPage_YellowPageTableRowCell');
\import('util_openOffice_SpreadsheetXml');

use ArrayObject;
use org\fktt\bstlist\util\openOffice\SpreadsheetXml;

class YellowPageTableRowCellList extends ArrayObject implements SpreadsheetXml
{
    /**
     * @param YellowPageTableRowCell $cell
     */
    public function append(YellowPageTableRowCell $cell)
    {
        parent::append($cell);
    }

    /**
     * @param YellowPageTableRowCell $cell
     */
    public function addCell(YellowPageTableRowCell $cell)
    {
        $this->append($cell);
    }

    /**
     * @return string
     */
    public function getAsSpreadsheetXml()
    {
        $str = "<table:table-row table:style-name=\"ro1\">";
        if ($this->count() > 0)
        {
            /** @var $cell YellowPageTableRowCell */
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
