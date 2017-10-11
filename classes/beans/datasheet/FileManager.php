<?php
namespace org\fktt\bstlist\beans\datasheet;

interface FileManager
{
    public function getFilesFromEpochWithFilter($epoch = "IV", $filter = array(), $country = null);
    public function getFilesFromEpochWithOrder($epoch = "IV", $order = "ORDER_SHORT", $country = null);
    public function getLatestFileFromEpoch($epoch);
}
