<?php
namespace org\fktt\bstlist\cmd;

\import('io_File');
\import('io_GlobIterator');
\import('util_logging_StdoutLogger');

use org\fktt\bstlist\io\File;
use org\fktt\bstlist\io\GlobIterator;
use org\fktt\bstlist\util\logging\StdoutLogger;

class CheckJNLPVersionCmd
{
    private static $log;
    private static $JNLP_FILE_NAME;

    private static $JNLP_HTTP_URI;
    private static $JNLP_FILE_URI;

    private static $LATEST_JAR;
    private static $WS_IMAGE_URL = "https://www.java.com/js/webstart.png";

    /**
     * @param string $jnlpFileName
     * @return CheckJNLPVersionCmd
     */
    public function __construct($jnlpFileName)
    {
        self::$log = new StdoutLogger(get_class($this));
        self::$JNLP_FILE_NAME = $jnlpFileName.".jnlp";
        self::$JNLP_FILE_URI = new File("rgzm/".self::$JNLP_FILE_NAME);
        self::$JNLP_HTTP_URI = self::$JNLP_FILE_URI->toHttpUrl();
        if (!self::$JNLP_FILE_URI->exists())
        {
            return $this;
        }

        // einlesen der gewuenschten Versionen
        $allVersions = new GlobIterator(self::$JNLP_FILE_URI->getPath()."/versions/rgzm_*.jar");
        self::$log->debug($allVersions->count());
        if ($allVersions->count() > 0)
        {
            $allVersions->seek($allVersions->count() - 1);
            $latest = $allVersions->current();
            /** @var $latest File */
            self::$LATEST_JAR = $latest->getBasename();
        }
        else
        {
            self::$LATEST_JAR = "";
        }
        self::$log->debug(self::$LATEST_JAR);
        return $this;
    }

    /**
     * @return bool
     */
    public function isEditorPresent()
    {
        return (self::$JNLP_FILE_URI->exists() && (\strlen(self::$LATEST_JAR) > 0));
    }

    /**
     * @return bool
     */
    public function doCommand()
    {
        self::$log->debug("Current file: ".self::$JNLP_FILE_URI->getPathname());
        if (!$this->isEditorPresent())
        {
            return false;
        }
        /** @var $xml \SimpleXMLElement */
        $xml = \simplexml_load_file(self::$JNLP_FILE_URI->getPathname());
        //self::$log->debug("<pre>".print_r($xml, true)."</pre>");
        //$result = $xml->xpath("/jnlp/resources/jar[starts-with(@href,'versions/rgzm') or starts-with(@href,'v*/rgzm')]");
        //self::$log->debug("<pre>".print_r($result, true)."</pre>");
        /** @noinspection PhpUndefinedFieldInspection */
        $jarLink = $xml->resources->jar[0]['href'];
        self::$log->debug($jarLink);
        $hasChanges = false;
        if (\basename($jarLink) != self::$LATEST_JAR)
        {
            self::$log->debug("Erneuere Jar-Link...");
            /** @noinspection PhpUndefinedFieldInspection */
            $xml->resources->jar[0]['href'] = "versions/".self::$LATEST_JAR;
            $hasChanges = true;
        }
        // check codebase and href for jnlp
        if ($xml['href'] != self::$JNLP_HTTP_URI)
        {
            self::$log->debug("Erneuere codebase und href...");
            $xml['href'] = self::$JNLP_HTTP_URI;
            $xml['codebase'] = dirname(self::$JNLP_HTTP_URI);
            $hasChanges = true;
        }
        if ($hasChanges)
        {
            self::$log->debug("Aenderungen in Datei sichern!");
            self::$log->debug($xml->asXML());
            // Dieses neue XML muss jetzt in die Datei zurueckgeschrieben werden!
            @\file_put_contents(self::$JNLP_FILE_URI->getPathname(), $xml->asXML());
        }
        return true;
    }

    /**
     * @param bool $useDeployScript [optional]
     * @return string
     */
    public function getDeploy($useDeployScript = false)
    {
        if ($useDeployScript)
        {
            // add javascript import to page head
            global $page;
            $page->head .= '<script src="https://www.java.com/js/deployJava.js"></script>';
            $ret = "";
            $ret .= "<!-- following script shows javaws launch application button -->\n";
            $ret .= "<script type=\"text/javascript\">\n";
            $ret .= "     deployJava.createWebStartLaunchButton('".self::$JNLP_HTTP_URI."', '1.7.0+');\n";
            //$ret .= "     /* alternatively launch application if page is loaded \n";
            //$ret .= "      deployJava.launch('".self::$JNLP_HTTP_URI."');*/\n";
            $ret .= "</script>";
            $ret .= "<noscript>";
            $ret .= self::$JNLP_FILE_URI->toDownloadLink("<img src=\"".self::$WS_IMAGE_URL.
                            "\" alt=\"Launch\"/>", false);
            $ret .= "</noscript>";
            return $ret;
        }
        else
        {
            return self::$JNLP_FILE_URI->toDownloadLink("<img src=\"".self::$WS_IMAGE_URL.
                    "\" alt=\"Java WS Launch Button\"/>", false);
        }
    }
}
