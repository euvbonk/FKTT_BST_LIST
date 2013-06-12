<?php

import('de_brb_hvl_wur_stumml_util_QI');

/**
 * Class BstFileSystem is a package protected helper class for resolving file paths
 * for this gpeasy plugin and should only be used by the File class below
 */
class BstFileSystem extends SplFileInfo
{
    private static $HTTP_HOST;
    private static $DOCUMENT_ROOT;
    private static $DIR_PREFIX;
    private static $ADDON_DIR;
    private static $DATA_DIR;

    public function __construct($filePath)
    {
        parent::__construct(self::resolveToAbsolutePath($filePath));
        return $this;
    }

    protected static function resolveToAbsolutePath($filePath)
    {
        if ($filePath == null)
        {
            $filePath = self::getDataDirectory();
        }
        else if (!file_exists($filePath) && (!is_dir($filePath) || is_file($filePath)))
        {
            // TODO check on ./ / . at beginning of filepath
            $filePath = self::getDataDirectory()."/".$filePath;
        }
        return $filePath;
    }

    public function toHttpUrl()
    {
        return str_replace('index.php/', '',
            QI::getUriFrom(str_replace(self::$DOCUMENT_ROOT.self::$DIR_PREFIX, '', $this->getPathname())));
        //return "http://".self::$HTTP_HOST."/".str_replace($_SERVER['DOCUMENT_ROOT'], '', $this->getPathname());
    }

    public function toDownloadLink($label, $addLastChange = true)
    {
        $uri = $this->toHttpUrl(); //self::getHttpUriForFile($file);
        if (strlen($uri) > 0 && file_exists($this->getPathname()))
        {
            $ret = HtmlUtil::toUtf8("<a href=\"".$uri."\" title=\"".strip_tags($label)."\">".$label."</a>");
            if ($addLastChange && file_exists($this->getPathname()))
            {
                //$ret .= "&nbsp;(" . strftime("%a, %d. %b %Y %H:%M", filemtime($file)) . ")";
                $ret .= "&nbsp;(".strftime("%a, %d. %b %Y %H:%M", $this->getMTime()).")";
            }
            return $ret;
        }
        else
        {
            return "<span style='font-weight: bold'>\"File does not exist!\"</span>";
        }
    }

    public static function setPaths($httpHost, $documentRoot, $dirPrefix, $addonDir)
    {
        self::$HTTP_HOST = $httpHost;
        self::$DOCUMENT_ROOT = realpath($documentRoot);
        self::$DIR_PREFIX = dirname($dirPrefix);
        self::$ADDON_DIR = realpath(dirname($addonDir));
        self::$DATA_DIR = self::$DOCUMENT_ROOT.self::$DIR_PREFIX."/data/_uploaded/file/fktt";
        //print "HTTP Host: ".self::$HTTP_HOST."<br/>Document root: ".self::$DOCUMENT_ROOT."<br> Dir prefix: ".
        //        self::$DIR_PREFIX."<br> Addon dir: ".self::$ADDON_DIR."<br>Data dir: ".self::$DATA_DIR."<br>";
    }

    protected static function getAddonTemplateDirectory()
    {
        return self::$ADDON_DIR."/templates/";
    }

    protected static function getDataDirectory()
    {
        return self::$DATA_DIR;
    }
}

class File extends BstFileSystem //SplFileInfo
{
    /**
     * @param string $filePath
     * @return File
     */
    public function __construct($filePath = null)
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
     * Compares two files by their last modification time and returns
     *  0 if and only if the times of both files are equal
     * +1 if the time of the first given file is smaller
     * -1 if the time of the first given file is greater
     *
     * The function is used for sorting files by last modification time in
     * ascending order (last modified at the top) using php function usort.
     *
     * @static
     * @param SplFileInfo $a
     * @param SplFileInfo $b
     * @return int
     */
    public static function compareLastModified(SplFileInfo $a, SplFileInfo $b)
    {
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
     * @param bool        $recursive       [optional]
     * @return null|RecursiveIteratorIterator iterator for SplFileInfo-Objects
     */
    public function listFiles($filterClassName = null, $recursive = true)
    {
        if ($recursive && $this->exists())
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
