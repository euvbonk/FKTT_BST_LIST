<?php
interface GoodsTrafficPageContent
{
    public function getDaysOfWeek();
    
    public function getFilterCSV();
    
    public function getLengthPerCar();
 
    public function getMinTrainCount();

    public function getTableEntries();
    
    public function getTableFooter();
}
?>
