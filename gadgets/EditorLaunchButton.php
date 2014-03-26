<?php

defined('is_running') or die('Not an entry point...');

import('de_brb_hvl_wur_stumml_cmd_CheckJNLPVersionCmd');
use org\fktt\bstlist\cmd\CheckJNLPVersionCmd;

class EditorLaunchButton
{
    public function __construct()
    {
        $cmd = new CheckJNLPVersionCmd("fmx");
        $cmd->doCommand();
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
