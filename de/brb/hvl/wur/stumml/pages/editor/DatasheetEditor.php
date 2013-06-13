<?php

import('de_brb_hvl_wur_stumml_cmd_CheckJNLPVersionCmd');
import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_pages_Frame');

final class DatasheetEditor extends Frame
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
     * @return String
     */
    public final function getJNLPFileUrl()
    {
        return self::$JNLP_HTTP_URI;
    }

    /**
     * @param $label String
     * @return String
     */
    public final function getCertificateFileUrl($label)
    {
        $f = new File('rgzm/rgzm.cert');
        return $f->toDownloadLink($label);
    }
}
