<?php
namespace org\fktt\bstlist\io;

\import('html_util_HtmlUtil');
\import('util_QI');

use SplFileInfo;
use org\fktt\bstlist\html\util\HtmlUtil;
use org\fktt\bstlist\util\QI;

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

    private static $NOTGUILTYPATHS = array('.', '..', './', '../', './/', '..//', '/', '//');

    public function __construct($filePath)
    {
        parent::__construct(self::resolveToAbsolutePath($filePath));
        return $this;
    }

    protected static function resolveToAbsolutePath($filePath)
    {
        if ($filePath == null || \in_array($filePath, self::$NOTGUILTYPATHS))
        {
            $filePath = self::getDataDirectory();
        }
        // Wenn uebergebener Dateipfad weder den Pfad zum Datenverzeichnis oder Templateverzeichnis enthaelt dann
        // muss das bereinigt werden
        else if ((\strpos($filePath, self::getDataDirectory()) === false) &&
                (\strpos($filePath, self::getAddonTemplateDirectory()) === false)
        )
            // && (!file_exists($filePath) && (!is_dir($filePath) || is_file($filePath))))
        {
            $filePath = self::getDataDirectory().self::cleanPath($filePath);
        }
        return $filePath;
    }

    /**
     * Checks given $path on "./", "/", "." at beginning and remove these occurrences
     *
     * @param string $path
     * @return string
     */
    private static function cleanPath($path)
    {
        $retArray = array();
        foreach (\explode('/', $path) as $char)
        {
            if ((\strlen($char) > 0) && ($char != '.' && $char != '..'))
            {
                $retArray[] = $char;
            }
        }
        return "/".\implode('/', $retArray);
    }

    public function toHttpUrl()
    {
        return \str_replace('index.php/', '',
            QI::getUriFrom(\str_replace(self::$DOCUMENT_ROOT.self::$DIR_PREFIX, '', $this->getPathname())));
    }

    public function toDownloadLink($label, $addLastChange = true, $checkExistence = true)
    {
        $uri = $this->toHttpUrl();
        $checkExistence = $checkExistence ? \file_exists($this->getPathname()) : true;
        if (\strlen($uri) > 0 && $checkExistence)
        {
            $ret = HtmlUtil::toUtf8("<a href=\"".$uri."\" title=\"".\strip_tags($label)."\">".$label."</a>");
            if ($addLastChange && \file_exists($this->getPathname()))
            {
                $ret .= "&nbsp;(".\strftime("%a, %d. %b %Y %H:%M", $this->getMTime()).")";
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
        self::$DOCUMENT_ROOT = \realpath($documentRoot);
        self::$DIR_PREFIX = \dirname($dirPrefix);
        self::$ADDON_DIR = \realpath(dirname($addonDir));
        self::$DATA_DIR = self::$DOCUMENT_ROOT.self::$DIR_PREFIX."/data/_uploaded/file/fktt";
    }

    protected static function getAddonTemplateDirectory()
    {
        return self::$ADDON_DIR."/templates/";
    }

    protected static function getAddonIni()
    {
        return self::$ADDON_DIR."/Addon.ini";
    }

    protected static function getDataDirectory()
    {
        return self::$DATA_DIR;
    }
}

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;

class File extends BstFileSystem
{
    /**
     * @param string $filePath
     * @return File
     */
    public function __construct($filePath = null)
    {
        parent::__construct($filePath);
        $this->setInfoClass(\get_class($this));
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return \basename($this->getPathname());
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
            return \dirname($this->getPathname());
        }
        else
        {
            $array = \explode("/", $this->getPathname());
            // Falls im Dateipfad das letzte Element ein "/" war, so ist im Array das letzte
            // Element leer und es muss nochmals das letzte Element, was dann wirklich ein
            // Teil des Dateipfades ist entfernt werden
            if (\strlen(\array_pop($array)) <= 0)
            {
                \array_pop($array);
            }
            return \join("/", $array);
        }
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return \file_exists($this->getPathname());
    }

    /**
     * @param SplFileInfo $b
     * @return int
     */
    public function compareMTimeTo(\SplFileInfo $b)
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
    public static function compareLastModified(\SplFileInfo $a, \SplFileInfo $b)
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
        \chmod($this->getPathname(), $int);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        return \unlink($this->getPathname());
    }

    /**
     * @param string $string
     * @return int
     */
    public function endsWith($string)
    {
        return \preg_match("/".\preg_quote($string).'$/', $this->getPathname());
    }

    /**
     * @param string $string
     * @return bool
     */
    public function contains($string)
    {
        return (\strpos($this->getPathname(), $string) !== false) ? true : false;
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
            if ($filterClassName != null && \strlen($filterClassName) > 0)
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
            $iit->setInfoClass(\get_class($this));
            return $it;
        }
        return null;
    }
}
