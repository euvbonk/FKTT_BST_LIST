<?php

import('de_brb_hvl_wur_stumml_util_reportTable_ListRowCells');

interface AbstractListRowEntries extends ListRowCells
{
    public function getName();
    public function getShort();
    public function getSheetUrl();
}
?>
