<?php

import('de_brb_hvl_wur_stumml_goodsTraffic_GoodsTrafficList');
import('de_brb_hvl_wur_stumml_goodsTraffic_view_GoodsTrafficListEntryRow');

import('de_brb_hvl_wur_stumml_html_table_TableRow');
import('de_brb_hvl_wur_stumml_html_table_TableCell');
import('de_brb_hvl_wur_stumml_html_util_HtmlUtil');

class GoodsTrafficListEntryRowImpl implements GoodsTrafficList, GoodsTrafficListEntryRow
{
    private $color;
    private $checkBox;
    private $name;
    private $short;
    private $input;
    private $output;
    private $maxInputOutput;
    private $shortestTrack;
    private $longestTrack;
    
    public function __construct($c, $cb, $n, $s, $i, $o, $mio, $st, $lt)
    {
        $this->color = $c;
        $this->checkBox = $cb;
        $this->name = $n;
        $this->short = $s;
        $this->input = $i;
        $this->output = $o;
        $this->maxInputOutput = $mio;
        $this->shortestTrack = $st;
        $this->longestTrack = $lt;
    }

    public function getHtml()
    {
        $ret = "";
        $ret .= "\n<tr bgcolor=\"".$this->color."\">";
        $ret .= "<td><input type=\"checkbox\" name=\"check[]\" value=\"".$this->short."\"".(($this->checkBox) ? " checked=\"checked\" " : "")."/></td>";
        $ret .= "<td style=\"text-align:center;\">".HtmlUtil::toUtf8($this->short)."</td>";
        $ret .= "<td>".HtmlUtil::toUtf8($this->name)."</td>";
        $ret .= "<td style=\"text-align:center;\">".sprintf(GoodsTrafficList::FORMAT, $this->input)."</td>";
        $ret .= "<td style=\"text-align:center;\">".sprintf(GoodsTrafficList::FORMAT, $this->output)."</td>";
        $ret .= "<td style=\"text-align:center;\">".sprintf(GoodsTrafficList::FORMAT, $this->maxInputOutput)."</td>";
        $ret .= "<td style=\"text-align:center;\">".$this->shortestTrack."</td>";
        $ret .= "<td style=\"text-align:center;\">".$this->longestTrack."</td>";
        $ret .= "</tr>";
        return $ret;
    }
}
?>
