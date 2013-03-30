<?php

class File extends SplFileInfo
{
    /**
     * @param string $filePath
     * @return File
     */
    public function __construct($filePath)
    {
        parent::__construct($filePath);
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return basename($this->getPathname());
    }

    /**
     * @return File
     */
    public function getParentFile()
    {
        return new File($this->getParent());
    }

    /**
     * @return string
     */
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

    /**
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->getPathname());
    }

    /**
     * @param SplFileInfo $b
     * @return int
     */
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

    /**
     * @static
     * @param SplFileInfo $a
     * @param SplFileInfo $b
     * @return int
     */
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

    /**
     * @param $int
     */
    public function changeFileRights($int)
    {
        chmod($this->getPathname(), $int);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        return unlink($this->getPathname());
    }

    /**
     * @param string $string
     * @return int
     */
    public function endsWith($string)
    {
        return preg_match("/".preg_quote($string).'$/', $this->getPathname());
    }

    /**
     * @param string $string
     * @return bool
     */
    public function contains($string)
    {
        return (strpos($this->getPathname(), $string) !== false) ? true : false;
    }

    /**
     * @param null|string $filterClassName [optional]
     * @param bool $recursive [optional]
     * @return null|RecursiveIteratorIterator iterator for SplFileInfo-Objects
     */
    public function listFiles($filterClassName = null, $recursive = true)
    {
        if ($recursive)
        {
            $it = null;
            if ($filterClassName != null && strlen($filterClassName) > 0)
            {
                $it = new RecursiveIteratorIterator(new $filterClassName(new RecursiveDirectoryIterator($this->getPathname())));
            }
            else
            {
                // follow also symbolic links
                $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->getPathname(), FilesystemIterator::FOLLOW_SYMLINKS));
            }
            $iit = $it->getInnerIterator();
            /** @var $iit RecursiveDirectoryIterator */
            $iit->setInfoClass('File');
            return $it;
        }
        return null;
    }
}
