<?php
import('de_brb_hvl_wur_stumml_html_Html');

class TableHeadCell implements Html
{
    private $content;
    private $attribute;

    /**
     * @param string $content
     * possible param string $attribute
     * @return TableHeadCell
     */
    public function __construct($content)
    {
        $this->content = $content;
        if (func_num_args() == 2)
        {
            $argv = func_get_args();
            $this->attribute = $argv[1];
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        if ($this->attribute != "")
        {
            //return "<th ".$this->attribute.">".$this->content."</th>\n";
            return "<th ".$this->attribute.">".$this->content."</th>";
        }
        else
        {
            //return "<th>".$this->content."</th>\n";
            return "<th>".$this->content."</th>";
        }
    }
}
