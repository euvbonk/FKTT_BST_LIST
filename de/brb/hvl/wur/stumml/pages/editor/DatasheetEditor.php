<?php
namespace org\fktt\bstlist\pages\editor;

import('de_brb_hvl_wur_stumml_cmd_CheckJNLPVersionCmd');
import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_pages_Frame');
use org\fktt\bstlist\pages\Frame;
use org\fktt\bstlist\cmd\CheckJNLPVersionCmd;
use org\fktt\bstlist\io\File;

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
        return array('JNLPFileUrl','CertificateFileUrl');
    }

    /**
     * @return String
     */
    public final function JNLPFileUrl()
    {
        return self::$JNLP_HTTP_URI;
    }

    /**
     * @param $label String
     * @return String
     */
    public final function CertificateFileUrl($label)
    {
        $f = new File('rgzm/RgZm.cert');
        return $f->toDownloadLink($label);
    }
}
