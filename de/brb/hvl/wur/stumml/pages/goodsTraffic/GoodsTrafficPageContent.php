<?php

interface GoodsTrafficPageContent
{
    public function getDaysOfWeekOptionsUI();

    public function getFilterCSV();

    public function getLengthPerCar();

    public function getMinTrainCount();

    public function getTable();

    public function getEpochOptionsUI();
}
