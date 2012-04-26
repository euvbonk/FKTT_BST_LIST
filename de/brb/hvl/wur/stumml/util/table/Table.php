<?php
import('de_brb_hvl_wur_stumml_util_table_Html');

class Table implements Html
{
    private $rows;
    
    public function __construct()
    {
        $rows = array();
    }
    
    public function addRow(Html $row)
    {
        $this->rows[] = $row;
    }
    
    public function getHtml()
    {
        $ret = "<table>\n";
        foreach ($this->rows as $row)
        {
            $ret .= "   ".$row->getHtml();
        }
        $ret .= "</table>";
        return $ret;
    }
}
?>
