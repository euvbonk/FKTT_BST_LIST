<?php
namespace org\fktt\bstlist\util\reportTable;

\import('util_reportTable_ReportTableListProperties');

interface ReportTableList extends ReportTableListProperties
{
    public function getTableHead();
    
    public function getTableBody();
    
    public function getTableFoot();
    
    public function setRowSelectorEnabled($bool=false);
    
    public function setTableHead(ListRow $rows);
    
    public function setTableFoot(ListRow $rows);
    
    public function setTableBody(ListRow $rows);
}
