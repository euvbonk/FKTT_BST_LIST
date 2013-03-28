<?php

interface GoodsTrafficListRowEntries
{
    public function getIdentifier();
    public function getInput();
    public function getOutput();
    public function getMaxInOutput();
    public function getShortestTrack();
    public function getLongestTrack();
}
