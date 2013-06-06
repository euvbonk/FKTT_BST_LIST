<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_util_QI');
import('de_brb_hvl_wur_stumml_io_File');

class DatasheetEditorSettings extends Settings
{
    /**
     * @static instance
     * @return Settings|DatasheetEditorSettings
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
    //@Override
    protected function getTemplateFileName()
    {
        return 'datasheet_editor.php';
    }

    /**
     * @return String
     */
    public final function getCertificateUrl()
    {
        // TODO resolve this by File to Url
        return QI::getDataDir()."/rgzm/rgzm.cert";
    }

    private function __construct(){}
    private function __clone(){}
}