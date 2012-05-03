<?php

import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_editor_DatasheetEditorSettings');
import('de_brb_hvl_wur_stumml_pages_editor_DatasheetEditorPageContent');
import('de_brb_hvl_wur_stumml_cmd_CheckOnEditorVersionCmd');

final class DatasheetEditor extends Frame implements DatasheetEditorPageContent
{
    public function __construct()
    {
        parent::__construct(DatasheetEditorSettings::getInstance()->getTemplateFile());
        $cmd = new CheckOnEditorVersionCmd();
        $cmd->doCommand();
    }

    /**
     * @see abstract class Frame
     */    
    public final function getLastChangeTimestamp()
    {
        return DatasheetEditorSettings::getInstance()->lastAddonChange();
    }

    /**
     * @see Interface DatasheetEditorPageContent
     */
    public final function getJNLPFileUrl()
    {
        return DatasheetEditorSettings::getInstance()->getUrl();
    }

    /**
     * @see Interface DatasheetEditorPageContent
     */
    public final function getCertificateFileUrl($label)
    {
        $f = DatasheetEditorSettings::getInstance()->getCertificateUrl();
        if (file_exists($f))
        {
            $l = DatasheetEditorSettings::buildDownloadPath($f, $label);
            $l .= "&nbsp;(".strftime("%a, %d. %b %Y", filemtime($f)).")";//%H:%M
            return $l;
        }
        else
        {
            return "";
        }
    }
}
?>
