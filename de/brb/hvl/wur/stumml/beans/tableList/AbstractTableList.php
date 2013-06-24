<?php

abstract class AbstractTableList
{
    /**
     * @return ListRow
     */
    public abstract function getTableHeader();

    /**
     * @param $array (file list)
     */
    public abstract function buildTableEntries($array);

    /**
     * @return ListRow
     */
    public abstract function getTableEntries();

    /**
     * @return ListRow
     */
    public abstract function getTableFooter();
}
