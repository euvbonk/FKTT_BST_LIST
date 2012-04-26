<?php
import('de_brb_hvl_wur_stumml_util_table_Html');

class TableRow implements Html
{
    private $cells;
    
    public function __construct()
    {
        $cells = array();
    }

    public function addCell(Html $cell)
    {
        $this->cells[] = $cell;
    }
    
    public function getHtml()
    {
        $ret = "<tr>\n";
        foreach ($this->cells as $cell)
        {
            $ret .= "   ".$cell->getHtml();
        }
        $ret .= "</tr>\n";
        return $ret;
    }
}
?>
