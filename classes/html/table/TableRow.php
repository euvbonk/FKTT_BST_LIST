<?php
namespace org\fktt\bstlist\html\table;

\import('html_Html');

use org\fktt\bstlist\html\Html;

/**
 *
 */
class TableRow implements Html
{
    private $cells;
    private $attribute = "";

    /**
     * possible param string $attribute
     * @return TableRow
     */
    public function __construct()
    {
        $this->cells = array();
        if (\func_num_args() == 1)
        {
            $argv = \func_get_args();
            $this->attribute = $argv[0];
        }
        return $this;
    }

    /**
     * @param Html $cell
     */
    public function addCell(Html $cell)
    {
        $this->cells[] = $cell;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        //$ret = "<tr".((strlen($this->attribute)>0) ? " ".$this->attribute : "").">\n";
        $ret = "<tr".((\strlen($this->attribute) > 0) ? " ".$this->attribute : "").">";
        /** @var $cell Html */
        foreach ($this->cells as $cell)
        {
            //$ret .= "   ".$cell->getHtml();
            $ret .= $cell->getHtml();
        }
        //$ret .= "</tr>\n";
        $ret .= "</tr>";
        return $ret;
    }
}
