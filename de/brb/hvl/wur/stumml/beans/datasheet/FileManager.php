<?php

interface FileManager
{
    public function getFilesFromEpochWithFilter($epoch = "IV", $filter = array());
    public function getFilesFromEpochWithOrder($epoch = "IV", $order = "ORDER_SHORT");
    public function getLatestFileFromEpoch($epoch);
}
?>
