<?php

defined('is_running') or die('Not an entry point...');

import('de_brb_hvl_wur_stumml_cmd_CheckJNLPVersionCmd');

class EditorLaunchButton
{
    public function __construct()
    {
        // TODO: Put this somewhere else!
        // extend allowed upload file extensions used in this plugin
        $GLOBALS['upload_extensions_allow'] = array_merge($GLOBALS['upload_extensions_allow'], array('jar','jnlp','dtd','css','xsl'));

        // check and repair jnlp file if necessary
        $cmd = new CheckJNLPVersionCmd("editor");
        if ($cmd->doCommand())
        {
            echo '<div style="padding:0 0 5px 0;">';
            echo $cmd->getDeploy();
            echo '&nbsp;<span style="color:blue;">Editor</span>';
            echo '</div> ';
        }
        return $this;
    }

    public function EditorLaunchButton()
    {
        return self::__construct();
    }
}
