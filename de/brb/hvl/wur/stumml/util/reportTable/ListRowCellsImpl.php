<?php

import('de_brb_hvl_wur_stumml_util_reportTable_ListRowCells');

class ListRowCellsImpl implements ListRowCells
{
    private $cells;
    private $styles;

    public function __construct(array $cells, array $styles = array())
    {
        $this->cells = $cells;
        $this->styles = $styles;
    }

    public function getCellsContent()
    {
        return $this->cells;
    }

    public function getCellsStyle()
    {
        return $this->styles;
    }
}
