<?php

defined('is_running') or die('Not an entry point...');

class EditorLaunchButton
{
    public function __construct()
    {
        echo '<div style="padding:5px 0 5px 0;">';
        echo '   <!-- following script shows javaws launch application button -->';
        echo '   <script type="text/javascript">';
        echo '      /* <![CDATA[ */';
        echo "         deployJava.createWebStartLaunchButton('http://ttmodul.dodoner.de/stumml/fktt/data/_uploaded/file/fktt/rgzm/editor.jnlp', '1.5.0');";
        echo '         /* alternatively launch application if page is loaded ';
        echo "            deployJava.launch('http://ttmodul.dodoner.de/stumml/fktt/data/_uploaded/file/fktt/rgzm/editor.jnlp');";
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
