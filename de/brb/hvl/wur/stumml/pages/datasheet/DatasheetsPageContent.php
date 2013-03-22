<?php

interface DatasheetsPageContent
{
    public function getTable();
    public function getEpochOptionsUI();
    public function getOrderOptionsUI();
    public function getYellowPageLink();
    public function getCSVListLink();
    public function getZipBundleLink();
    public function getApplicationUrl();
}
