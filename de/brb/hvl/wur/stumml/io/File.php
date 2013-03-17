<?php

class File
{
    private $oPath = null;

    public function __construct($filePath)
    {
        $this->oPath = $filePath;
    }

    public function getName()
    {
        return basename($this->getPath());
    }

    public function getPath()
    {
        return $this->oPath;
    }

    public function getParentFile()
    {
        return new File($this->getParent());
    }

    public function getParent()
    {
        return dirname($this->getPath());
    }

    public function exists()
    {
        return file_exists($this->getPath());
    }

    public function compareMTimeTo(File $b)
    {
        $time_a = filemtime($this->getPath());
        $time_b = filemtime($b->getPath());
        if ($time_a == $time_b)
        {
            return 0;
        }
        return ($time_a < $time_b) ? +1 : -1;
    }

    public function changeFileRights($int)
    {
        chmod($this->getPath(), $int);
    }

    public function isFile()
    {
        return is_file($this->getPath());
    }

    public function isReadable()
    {
        return is_readable($this->getPath());
    }

    public function isWritable()
    {
        return is_writable($this->getPath());
    }

    public function delete()
    {
        return unlink($this->getPath());
    }

    public function endsWith($string)
    {
        return preg_match("/".preg_quote($string).'$/', $this->getPath());
    }

    public function contains($string)
    {
        return (strpos($this->getPath(), $string) !== false) ? true : false;
    }
}
