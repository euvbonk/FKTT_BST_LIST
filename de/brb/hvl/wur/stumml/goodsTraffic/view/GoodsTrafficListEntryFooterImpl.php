<?php

import('de_brb_hvl_wur_stumml_goodsTraffic_GoodsTrafficList');
import('de_brb_hvl_wur_stumml_goodsTraffic_view_GoodsTrafficListEntryFooter');

import('de_brb_hvl_wur_stumml_html_table_TableRow');
import('de_brb_hvl_wur_stumml_html_table_TableCell');

class GoodsTrafficListEntryFooterImpl implements GoodsTrafficList, GoodsTrafficListEntryFooter
{
    private $input;
    private $output;
    private $maxInputOutput;
    private $shortestTrack;
    private $longestTrack;

    public function __construct($i, $o, $mio, $st, $lt)
    {
        $this->input = $i;
        $this->output = $o;
        $this->maxInputOutput = $mio;
        $this->shortestTrack = $st;
        $this->longestTrack = $lt;
    }

    public function getHtml()
    {
        $ret = "";
        $ret .= "<td style=\"text-align:center;\">".sprintf(GoodsTrafficList::FORMAT, $this->input)."</td>";
        $ret .= "<td style=\"text-align:center;\">".sprintf(GoodsTrafficList::FORMAT, $this->output)."</td>";
        $ret .= "<td style=\"text-align:center;\">".sprintf(GoodsTrafficList::FORMAT, $this->maxInputOutput)."</td>";
        $ret .= "<td style=\"text-align:center;\">".$this->shortestTrack."</td>";
        $ret .= "<td style=\"text-align:center;\">".$this->longestTrack."</td>\n";
        return $ret;
    }
}
?>
