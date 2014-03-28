<?php
namespace org\fktt\bstlist\beans\yellowpage;

\import('beans_yellowPage_YellowPageConverter');
\import('util_openOffice_SpreadsheetDocument');

use SimpleXMLElement;
use org\fktt\bstlist\util\openOffice\SpreadsheetDocument;

class YellowPageSpreadsheetGenerator extends SpreadsheetDocument implements YellowPageConverter
{
    private $oYellowPage = null;

    /**
     * @param array $list [optional]
     * @return YellowPageSpreadsheetGenerator
     */
    public function __construct($list = array())
    {
        parent::__construct();
        $this->setCellPositionByIndex(0, 1);
        if (!empty($list))
        {
            $this->setYellowPage($list);
        }
        return $this;
    }

    /**
     * @param array $yellowPage
     */
    public function setYellowPage(/*List<YellowPageTableRow>*/ $yellowPage)
    {
        $this->oYellowPage = $yellowPage;
    }

    /**
     * @return null|array
     */
    public function getYellowPage()
    {
        return $this->oYellowPage;
    }

    /**
     * @see interface YellowPageConverter
     */
    public function generate()
    {
        /*$currentColumn = $this->getCurrentCellPosition()->x;
        $currentRow = $this->getCurrentCellPosition()->y;
        
        foreach ($this->getYellowPage() as $row)
        {
            // List contains Objects of YellowPageTableRow
            foreach ($row->getYellowPageTableRowCells() as $cell)
            {
                // List contains Objects of YellowPageTableRowCells
                $this->setTextAtCellPositionByIndex($cell->getContent(), $currentColumn, $currentRow);
                $currentColumn++;
            }
            $currentColumn = 0;
            $currentRow++;
        }*/
        $currentString = $this->getDocument()->asXML();
        $posOfLastTableRow = \strpos($currentString, "</table:table-row>")+\strlen("</table:table-row>");
        $posUntil = \strpos($currentString, 'number-rows-repeated="')+\strlen('number-rows-repeated="');
        $newString = \substr_replace($currentString, $this->getYellowPage(), $posOfLastTableRow, $posUntil-$posOfLastTableRow);
        $this->setDocument(new SimpleXMLElement(\preg_replace('/&[^; ]{0,6}.?/e', "((substr('\\0',-1) == ';') ? '\\0' : '&amp;'.substr('\\0',1))", $newString)));
        //print $posOfLastTableRow." + ".strlen("</table:table-row>")." (".$posUntil.") <br/>";
    }
}
