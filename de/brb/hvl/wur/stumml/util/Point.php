<?php

class Point
{
    public $x;
    public $y;
    
    public function __construct($x = 0, $y = 0)
    {
        $this->setLocation($x, $y);
    }

    public function setLocation($x, $y)
    {
        $this->x = (int) $x;
        $this->y = (int) $y;
    }

    public function getLocation()
    {
        return new Point($this->x, $this->y);
    }
}

?>
