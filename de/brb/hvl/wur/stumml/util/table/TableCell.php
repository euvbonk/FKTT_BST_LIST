<?php
import('de_brb_hvl_wur_stumml_util_table_Html');

class TableCell implements Html
{
    private $content;
    private $attribute;

    public function __construct($content)
    {
        $this->content = $content;
        if (func_num_args() == 2)
        {
            $argv = func_get_args();
            $this->attribute = $argv[1];
        }
    }

    public function getHtml()
    {
        if ($this->attribute != "")
        {
            return "<td ".$this->attribute.">".$this->content."</td>\n";
        }
        else
        {
            return "<td>".$this->content."</td>\n";
        }
    }
}
?>
