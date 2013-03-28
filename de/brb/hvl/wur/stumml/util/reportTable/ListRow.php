<?php

import('de_brb_hvl_wur_stumml_util_reportTable_ListRowCells');

class ListRow extends ArrayObject
{
    /**
     * @param ListRowCells $cells
     */
    public function append(ListRowCells $cells)
    {
        parent::append($cells);
    }

/*    public function offsetSet($index, ListRowCells $newval)
    {
        parent::offsetSet($index, $newval);
    }
*/
}
