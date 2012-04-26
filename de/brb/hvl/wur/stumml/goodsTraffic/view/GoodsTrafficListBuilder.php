<?php

import('de_brb_hvl_wur_stumml_html_Html');

import('de_brb_hvl_wur_stumml_goodsTraffic_view_GoodsTrafficListEntryRow');

class GoodsTrafficListBuilder implements Html
{
    private $tableEntries = null;

    public function __construct()
    {
        $this->tableEntries = "";
    }

    public function addTableRow(GoodsTrafficListEntryRow $row)
    {
        $this->tableEntries .= $row->getHtml();
    }

    public function getHtml()
    {
        return $this->tableEntries;
    }
}
?>
