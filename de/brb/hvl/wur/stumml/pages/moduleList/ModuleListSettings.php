<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_io_File');

class ModuleListSettings extends Settings
{
    /**
     * @static instance
     * @return Settings|ModuleListSettings
     */
    public static function getInstance()
    {
        if (null === parent::$INSTANCE)
        {
            parent::$INSTANCE = new self;
        }
        return parent::getInstance();
    }

    /**
     * @return String
     */
    protected function getTemplateFileName()
    {
        return 'module_list.php';
    }

    private function __construct(){}
    private function __clone(){}
}