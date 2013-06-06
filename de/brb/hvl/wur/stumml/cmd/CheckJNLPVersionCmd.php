<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_util_logging_StdoutLogger');

class CheckJNLPVersionCmd
{
    private static $log;
    private static $JNLP_FILE_NAME;

    private static $JNLP_HTTP_URI;
    private static $JNLP_FILE_URI;

    private static $LATEST_JAR;
    private static $WS_IMAGE_URL = "http://www.java.com/js/webstart.png";

    /**
     * @param string $jnlpFileName
     * @return CheckJNLPVersionCmd
     */
    public function __construct($jnlpFileName)
    {
        self::$log = new StdoutLogger(get_class($this));
        self::$JNLP_FILE_NAME = $jnlpFileName.".jnlp";
        self::$JNLP_HTTP_URI = Settings::getHttpUriForFile('rgzm/'.self::$JNLP_FILE_NAME);
        self::$JNLP_FILE_URI = new File("rgzm/".self::$JNLP_FILE_NAME);
        if (!self::$JNLP_FILE_URI->exists())
        {
            return $this;
        }

        // einlesen der gewuenschten Versionen
        $allVersions = new GlobIterator(self::$JNLP_FILE_URI->getPath()."/versions/rgzm_*.jar");
        self::$log->debug($allVersions->count());
        if ($allVersions->count() > 0)
        {
            $allVersions->seek($allVersions->count()-1);
            self::$LATEST_JAR = $allVersions->getBasename();
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
    public function doCommand()
    {
        self::$log->debug("Current file: ".self::$JNLP_FILE_URI->getPathname());
        if (!self::$JNLP_FILE_URI->exists())
        {
            return false;
        }
        /** @var $xml SimpleXMLElement */
        $xml = simplexml_load_file(self::$JNLP_FILE_URI->getPathname());
        //self::$log->debug("<pre>".print_r($xml, true)."</pre>");
        //$result = $xml->xpath("/jnlp/resources/jar[starts-with(@href,'versions/rgzm') or starts-with(@href,'v*/rgzm')]");
        //self::$log->debug("<pre>".print_r($result, true)."</pre>");
        $jarLink = $xml->resources->jar[0]['href'];
        self::$log->debug($jarLink);
        $hasChanges = false;
        if (basename($jarLink) != self::$LATEST_JAR)
        {
            self::$log->debug("Erneuere Jar-Link...");
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
            @file_put_contents(self::$JNLP_FILE_URI->getPathname(), $xml->asXML());
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
            $ret = "";
            $ret .= "<!-- following script shows javaws launch application button -->\n";
            $ret .= "<script type=\"text/javascript\">\n";
            $ret .= "   /* <![CDATA[ */";
            $ret .= "     deployJava.createWebStartLaunchButton('".self::$JNLP_HTTP_URI."', '1.6.0+');\n";
            //$ret .= "     /* alternatively launch application if page is loaded \n";
            //$ret .= "      deployJava.launch('".self::$JNLP_HTTP_URI."');*/\n";
            $ret .= "   /* ]]> */";
            $ret .= "</script>";
            return $ret;
        }
        else
        {
            return Settings::getDownloadLinkForFile(self::$JNLP_FILE_URI->getPathname(), "<img src=\"".self::$WS_IMAGE_URL.
                    "\"  style=\"position:relative;top:5px;\" alt=\"Java WS Launch Button\"/>", false);
        }
    }
}
