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

    /**
     * @return String
     */
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
        if ($this->isFile())
        {
            return dirname($this->getPath());
        }
        else
        {
            $array = explode("/", $this->getPath());
            // Falls im Dateipfad das letzte Element ein "/" war, so ist im Array das letzte
            // Element leer und es muss nochmals das letzte Element, was dann wirklich ein
            // Teil des Dateipfades ist entfernt werden
            if (strlen(array_pop($array)) <= 0)
            {
                array_pop($array);
            }
            return join("/", $array);
        }
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
