<?php
import('de_brb_hvl_wur_stumml_html_Html');

class Table implements Html
{
    private $rows;

    /**
     * @return Table
     */
    public function __construct()
    {
        $this->rows = array();
        return $this;
    }

    /**
     * @param Html $row
     */
    public function addRow(Html $row)
    {
        $this->rows[] = $row;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        $ret = "<table>\n";
        /** @var $row Html */
        foreach ($this->rows as $row)
        {
            $ret .= "   ".$row->getHtml();
        }
        $ret .= "</table>";
        return $ret;
    }
}
