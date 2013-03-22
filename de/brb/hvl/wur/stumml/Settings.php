<?php

import('de_brb_hvl_wur_stumml_util_QI');
import('de_brb_hvl_wur_stumml_html_util_HtmlUtil');

abstract class Settings
{
    private static $INSTANCE = null;
    
    public static function getInstance()
    {
        if (null === self::$INSTANCE)
        {
            self::$INSTANCE = new self;
        }
        return self::$INSTANCE;
    }

    public function lastAddonChange()
    {
        return '22. M&auml;rz 2013 19:00:00';
    }

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

    public final static function uploadDir()
    {
        return self::uploadBaseDir().'/db';
    }

    public final static function getHttpUriForFile($filePath)
    {
        $path = QI::getRelativeDataDir().'/'.$filePath;
        if (!file_exists($path))
        {
            $path = substr($filePath, strlen(QI::getRootDir())+1);
        }
        return str_replace('index.php/', '', QI::getUriFrom($path));
    }

    public final static function getDownloadLinkForFile($file, $label, $addLastChange = true)
    {
        $uri = self::getHttpUriForFile($file);
        if (strlen($uri) > 0)
        {
            $ret = HtmlUtil::toUtf8("<a href=\"".$uri."\" title=\"".$label."\">".$label."</a>");
            if ($addLastChange)
            {
                $ret .= "&nbsp;(" . strftime("%a, %d. %b %Y %H:%M", filemtime($file)) . ")";
            }
            return $ret;
        }
        else
        {
            return "File does not exist!";
        }
    }

    /*public final static function buildDownloadPath($filePath, $label)
    {
        $filePath = substr($filePath, strlen(QI::getRootDir())+1);
        return str_replace('index.php/', '', HtmlUtil::toUtf8(QI::buildAbsoluteLink($filePath, $label)));
    }*/


    /**
     * @abstract
     * @return File
     */
    public abstract function getTemplateFile();

    private function __construct(){}
    private function __clone(){}
}
