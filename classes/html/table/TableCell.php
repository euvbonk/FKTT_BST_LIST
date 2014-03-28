<?php
namespace org\fktt\bstlist\html\table;

\import('html_Html');

use org\fktt\bstlist\html\Html;

class TableCell implements Html
{
    private $content;
    private $attribute;

    /**
     * @param string $content
     * possible param string $attribute
     * @return TableCell
     */
    public function __construct($content)
    {
        $this->content = $content;
        if (\func_num_args() == 2)
        {
            $argv = \func_get_args();
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
            //return "<td ".$this->attribute.">".$this->content."</td>\n";
            return "<td ".$this->attribute.">".$this->content."</td>";
        }
        else
        {
            //return "<td>".$this->content."</td>\n";
            return "<td>".$this->content."</td>";
        }
    }
}
