<?php
namespace org\fktt\bstlist\util\reportTable;

\import('html_Html');
\import('html_table_Table');
\import('html_table_TableRow');
\import('html_table_TableCell');
\import('html_table_TableHeadCell');
\import('util_reportTable_ReportTableList');
\import('util_reportTable_ListRow');

use org\fktt\bstlist\html\Html;
use /** @noinspection PhpUnusedAliasInspection */
    org\fktt\bstlist\html\table\Table;
use org\fktt\bstlist\html\table\TableRow;
use /** @noinspection PhpUnusedAliasInspection */
    org\fktt\bstlist\html\table\TableCell;
use /** @noinspection PhpUnusedAliasInspection */
    org\fktt\bstlist\html\table\TableHeadCell;

class ReportTableListImpl implements ReportTableList, Html
{
    private $cHeader = null;
    private $cEntries = null;
    private $cFooter = null;
    private $cSelector = false;

    /**
     * @param bool $bool [optional]
     */
    public function setRowSelectorEnabled($bool = false)
    {
        $this->cSelector = $bool;
    }

    /**
     * @param ListRow $rows
     */
    public function setTableHead(ListRow $rows)
    {
        $this->cHeader = $rows;
    }

    /**
     * @param ListRow $rows
     */
    public function setTableBody(ListRow $rows)
    {
        $this->cEntries = $rows;
    }

    /**
     * @param ListRow $rows
     */
    public function setTableFoot(ListRow $rows)
    {
        $this->cFooter = $rows;
    }

    /**
     * @return string
     */
    public function getTableHead()
    {
        return $this->buildRows("thead", $this->cHeader, ReportTableListProperties::HEAD_ROW_COLOR, "org\\fktt\\bstlist\\html\\table\\TableHeadCell");
    }

    /**
     * @return string
     */
    public function getTableBody()
    {
        return $this->buildRows("tbody", $this->cEntries, array(ReportTableListProperties::ODD, ReportTableListProperties::EVEN));
    }

    /**
     * @return string
     */
    public function getTableFoot()
    {
        return $this->buildRows("tfoot", $this->cFooter, ReportTableListProperties::FOOT_ROW_COLOR);
    }

    /**
     * @param string       $struct
     * @param ListRow|null $rows [optional]
     * @param string       $rowColor
     * @param string       $cellForm [optional]
     * @return string
     */
    protected function buildRows($struct, ListRow $rows = null, $rowColor, $cellForm = "org\\fktt\\bstlist\\html\\table\\TableCell")
    {
        //$str = "<".$struct.">\n";
        $str = "<".$struct.">";
        if ($rows != null)
        {
        foreach ($rows as $ind => $row)
        {
            //print "<pre>".print_r($row, true)."</pre>";
            if (\is_array($rowColor))
            {
                //print ($ind %2)."<br/>";
                $trowColor = $rowColor[($ind %2)];
            }
            else
            {
                $trowColor = $rowColor;
            }
            $trow = new TableRow("style=\"background-color:".$trowColor.";\"");
            if ($this->cSelector && $struct == "thead" && $ind == 0)
            {
                $trow->addCell(new $cellForm("X", "rowspan=\"".$rows->count()."\""));
            }
            /** @var $row ListRowCells */
            foreach ($row->getCellsContent() as $key => $cell)
            {
                if ($this->cSelector && $key == 0 && $struct == "tbody")
                {
                    $trow->addCell(new $cellForm("<input type=\"checkbox\" name=\"check[]\" value=\"".$cell."\"/>", "style=\"text-align:center;\"")); //".(($this->checkBox) ? " checked=\"checked\" " : "")."
                }
                else
                {
                    $attr = "";
                    if (\array_key_exists($key, $row->getCellsStyle()))
                    {
                        $t = $row->getCellsStyle();
                        $attr = $t[$key];
                    }
                    //print "<pre>".print_r($cell, true)."</pre>";
                    $trow->addCell(new $cellForm($cell, $attr));
                }
            }
            $str .= $trow->getHtml();
        }}
        //$str .= "</".$struct.">\n";
        $str .= "</".$struct.">";
        return $str;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        $str = "";
        $str .= ($this->cHeader != null) ? $this->getTableHead() : "";
        $str .= ($this->cEntries != null) ? $this->getTableBody() : "";
        $str .= ($this->cFooter != null) ? $this->getTableFoot() : "";
        //return (strlen($str) > 0) ? "<table cellspacing=\"1\">\n".$str."</table>\n" : "Nothing to display!";
        return (\strlen($str) > 0) ? "<table style=\"border-spacing:1px;border-collapse: separate;\">".$str."</table>" : "Nothing to display!";
    }

    /**
     * @return string
     */
    //@Override
    public function __toString()
    {
        return "<table>".$this->cHeader.$this->cEntries.$this->cFooter."</table>";
    }
}
