<?php

import('de_brb_hvl_wur_stumml_io_TemplateFile');
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

    /**
     * @return String
     */
    // TODO Funktion in Klasse Frame konzentrieren und Klasse Settings beseitigen
    public final function lastAddonChange()
    {
        return '06. Juni 2013 20:00:00';
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
    // TODO Funktion in Klasse Frame konzentrieren und Klasse Settings beseitigen
    public final function getTemplateFile()
    {
        return new TemplateFile(self::getInstance()->getTemplateFileName());
    }

    private function __construct(){}
    private function __clone(){}
}
