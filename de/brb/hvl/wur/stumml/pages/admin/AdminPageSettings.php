<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_io_File');

class AdminPageSettings extends Settings
{
    /**
     * @static instance
     * @return Settings|AdminPageSettings
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
    /*@Override*/
    public function lastAddonChange()
    {
        return '05. April 2013 20:00:00';
    }

    /**
     * @return String
     */
    //@Override
    protected function getTemplateFileName()
    {
        return 'admin.php';
    }

    private function __construct(){}
    private function __clone(){}
}