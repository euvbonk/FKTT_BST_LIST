<?php
import('de_brb_hvl_wur_stumml_html_Html');

class TableHeadCell implements Html
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
            return "<th ".$this->attribute.">".$this->content."</th>\n";
        }
        else
        {
            return "<th>".$this->content."</th>\n";
        }
    }
}
?>
