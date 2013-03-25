<?php

class File extends SplFileInfo
{
    public function __construct($filePath)
    {
        parent::__construct($filePath);
    }

    public function getName()
    {
        return basename($this->getPathname());
    }

    public function getParentFile()
    {
        return new File($this->getParent());
    }

    public function getParent()
    {
        if ($this->isFile())
        {
            return dirname($this->getPathname());
        }
        else
        {
            $array = explode("/", $this->getPathname());
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
        return file_exists($this->getPathname());
    }

    public function compareMTimeTo(SplFileInfo $b)
    {
        /*$time_a = $this->getMTime();
        $time_b = $b->getMTime();
        if ($time_a == $time_b)
        {
            return 0;
        }
        return ($time_a < $time_b) ? +1 : -1;*/
        return self::compareLastModified($this, $b);
    }

    public static function compareLastModified(SplFileInfo $a, SplFileInfo $b)
    {
        // TODO this function does not work as expected!
        $time_a = $a->getMTime();
        $time_b = $b->getMTime();
        if ($time_a == $time_b)
        {
            return 0;
        }
        return ($time_a < $time_b) ? +1 : -1;
    }

    public function changeFileRights($int)
    {
        chmod($this->getPathname(), $int);
    }

    public function delete()
    {
        return unlink($this->getPathname());
    }

    public function endsWith($string)
    {
        return preg_match("/".preg_quote($string).'$/', $this->getPathname());
    }

    public function contains($string)
    {
        return (strpos($this->getPathname(), $string) !== false) ? true : false;
    }
}
