<?php
namespace org\fktt\bstlist\util\reportTable;

\import('util_reportTable_ListRowCells');

class ListRowCellsImpl implements ListRowCells
{
    private $cells;
    private $styles;

    /**
     * @param array $cells
     * @param array $styles [optional]
     * @return ListRowCellsImpl
     */
    public function __construct(array $cells, array $styles = array())
    {
        $this->cells = $cells;
        $this->styles = $styles;
        return $this;
    }

    /**
     * @return array
     */
    public function getCellsContent()
    {
        return $this->cells;
    }

    /**
     * @return array
     */
    public function getCellsStyle()
    {
        return $this->styles;
    }
}
