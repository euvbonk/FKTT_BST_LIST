<?php

import('de_brb_hvl_wur_stumml_cmd_CheckJNLPVersionCmd');
import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_pages_Frame');
import('de_brb_hvl_wur_stumml_pages_editor_DatasheetEditorPageContent');

final class DatasheetEditor extends Frame implements DatasheetEditorPageContent
{
    private static $JNLP_HTTP_URI;

    public function __construct()
    {
        parent::__construct('datasheet_editor');
        $cmd = new CheckJNLPVersionCmd("editor");
        self::$JNLP_HTTP_URI = ($cmd->doCommand()) ? $cmd->getDeploy() : "";
        return $this;
    }

    protected function getCallableMethods()
    {
        return array('getJNLPFileUrl','getCertificateFileUrl');
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
        $f = new File('rgzm/rgzm.cert');
        return $f->toDownloadLink($label);
    }
}
