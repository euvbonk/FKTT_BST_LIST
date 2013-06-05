<?php

import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_util_QI');
import('de_brb_hvl_wur_stumml_html_util_HtmlUtil');

abstract class Settings
{
    protected static $INSTANCE = null;

    /**
     * @static instance
     * @return Settings
     */
    protected static function getInstance()
    {
        return self::$INSTANCE;
    }

    public function lastAddonChange()
    {
        return '05. Juni 2013 20:00:00';
    }

    /**
     * @deprecated
     * @return string
     */
    public final static function uploadBaseDir()
    {
        return QI::getDataDir();
    }
    
    public final static function addonBaseDir()
    {
        return QI::getAddonPathCode();
    }
    
    public final static function addonTemplateBaseDir()
    {
        return self::addonBaseDir().'/templates';
    }

    /**
     * @deprecated
     * @return string
     */
    public final static function uploadDir()
    {
        return self::uploadBaseDir().'/db';
    }

    // TODO auslagern in eigene FileSystemResolver- bzw. WebUtil-Klasse
    public final static function getHttpUriForFile($filePath)
    {
        $path = QI::getRelativeDataDir().'/'.$filePath;
        if (!file_exists($path))
        {
            $path = substr($filePath, strlen(QI::getRootDir())+1);
        }
        return str_replace('index.php/', '', QI::getUriFrom($path));
    }

    /**
     * @static
     * @param      $file
     * @param      $label
     * @param bool $addLastChange [optional]
     * @return string
     */
    // TODO auslagern in eigene FileSystemResolver- bzw. WebUtil-Klasse
    public final static function getDownloadLinkForFile($file, $label, $addLastChange = true)
    {
        $uri = self::getHttpUriForFile($file);
        if (strlen($uri) > 0 && file_exists($file))
        {
            $ret = HtmlUtil::toUtf8("<a href=\"".$uri."\" title=\"".strip_tags($label)."\">".$label."</a>");
            if ($addLastChange && file_exists($file))
            {
                $ret .= "&nbsp;(" . strftime("%a, %d. %b %Y %H:%M", filemtime($file)) . ")";
            }
            return $ret;
        }
        else
        {
            return "<span style='font-weight: bold'>\"File does not exist!\"</span>";
        }
    }

    /**
     * @abstract
     * @return String
     */
    protected abstract function getTemplateFileName();

    /**
     * @abstract
     * @return File
     */
    public final function getTemplateFile()
    {
        return new File(self::addonTemplateBaseDir()."/".self::getInstance()->getTemplateFileName());
    }

    private function __construct(){}
    private function __clone(){}
}
