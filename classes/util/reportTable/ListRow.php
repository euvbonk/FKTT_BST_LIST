<?php
namespace org\fktt\bstlist\util\reportTable;

\import('util_reportTable_ListRowCells');

use ArrayObject;

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
