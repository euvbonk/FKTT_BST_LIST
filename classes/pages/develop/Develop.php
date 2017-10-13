<?php
namespace org\fktt\bstlist\pages\develop;

\import('pages_Frame');
\import('util_logging_StdoutLogger');

use org\fktt\bstlist\pages\Frame;
use org\fktt\bstlist\util\logging\StdoutLogger;

final class Develop extends Frame
{
    private static $log;

    public function __construct()
    {
        parent::__construct();
        self::$log = new StdoutLogger(\get_class($this));
        return $this;
    }

    public function content()
    {
        \ob_start();
        self::$log->debug("No Testing!");
        print "No Testing";
        $str = \ob_get_contents();
        \ob_end_clean();
        return $str;
    }

    protected function getCallableMethods()
    {
        return array();
    }

    public final function showContent()
    {
        print "<h3>Ausgabe von Daten f&uuml;r Entwicklungszwecke</h3>";
        print "<p>".$this->content()."</p>";
        print "<hr />";
    }
}
