<?php

defined('is_running') or die('Not an entry point...');

import('de_brb_hvl_wur_stumml_Settings');

class EditorLaunchButton
{
    public function __construct()
    {
        $URL = Settings::getHttpUriForFile('rgzm/editor.jnlp');
        // extend allowed upload file extensions used in this plugin
        $GLOBALS['upload_extensions_allow'] = array_merge($GLOBALS['upload_extensions_allow'], array('jar','jnlp','dtd','css','xsl'));
        echo '<div style="padding:5px 0 5px 0;">';
        echo '   <!-- following script shows javaws launch application button -->';
        echo '   <script type="text/javascript">';
        echo '      /* <![CDATA[ */';
        echo "         deployJava.createWebStartLaunchButton('".$URL."', '1.5.0');";
        echo '         /* alternatively launch application if page is loaded ';
        echo "            deployJava.launch('".$URL."');";
        echo '          */';
        echo '      /* ]]> */';
        echo '   </script>&nbsp;<span style="color:blue;position:relative;top:-5px;">Editor</span>';
        echo '</div> ';
    }

    public function EditorLaunchButton()
    {
        self::__construct();
    }
}
