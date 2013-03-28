<?php

class Point
{
    public $x;
    public $y;

    /**
     * @param int $x
     * @param int $y
     * @return Point
     */
    public function __construct($x = 0, $y = 0)
    {
        $this->setLocation($x, $y);
        return $this;
    }

    /**
     * @param int $x
     * @param int $y
     */
    public function setLocation($x, $y)
    {
        $this->x = (int) $x;
        $this->y = (int) $y;
    }

    /**
     * @return Point
     */
    public function getLocation()
    {
        return new Point($this->x, $this->y);
    }
}
