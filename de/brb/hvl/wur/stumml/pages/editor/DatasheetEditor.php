<?php

import('de_brb_hvl_wur_stumml_cmd_CheckJNLPVersionCmd');
import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_editor_DatasheetEditorSettings');
import('de_brb_hvl_wur_stumml_pages_editor_DatasheetEditorPageContent');

final class DatasheetEditor extends Frame implements DatasheetEditorPageContent
{
    private static $JNLP_HTTP_URI;

    public function __construct()
    {
        parent::__construct(DatasheetEditorSettings::getInstance()->getTemplateFile());
        $cmd = new CheckJNLPVersionCmd("editor");
        self::$JNLP_HTTP_URI = ($cmd->doCommand()) ? $cmd->getDeploy() : "";
        return $this;
    }

    /**
     * @see abstract class Frame
     * @return String
     */
    //@Override
    public final function getLastChangeTimestamp()
    {
        return DatasheetEditorSettings::getInstance()->lastAddonChange();
    }

    /**
     * @see Interface DatasheetEditorPageContent
     * @return String
     */
    public final function getJNLPFileUrl()
    {
        return self::$JNLP_HTTP_URI;
    }

    /**
     * @see Interface DatasheetEditorPageContent
     * @param $label String
     * @return String
     */
    public final function getCertificateFileUrl($label)
    {
        $f = DatasheetEditorSettings::getInstance()->getCertificateUrl();
        return DatasheetEditorSettings::getDownloadLinkForFile($f, $label);
    }
}
