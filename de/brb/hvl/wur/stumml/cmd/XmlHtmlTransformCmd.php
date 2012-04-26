<?php

final class XmlHtmlTransformCmd
{
    private static $XSL_FILE = "bahnhof.xsl";
    private $oHtmlFile;

    public function __construct()
    {
        $this->oHtmlFile = null;
    }

    public function doCommand($xmlFile)
    {
        // endsWith
        if (!preg_match("/".preg_quote("xml") .'$/', $xmlFile) || !is_writable(dirname($xmlFile))) return false;
        
        $this->oHtmlFile = null;
        $htmlFile = dirname($xmlFile).DIRECTORY_SEPARATOR.basename($xmlFile, "xml")."html";
        if (!file_exists($htmlFile) || filemtime($htmlFile) < filemtime($xmlFile))
        {
            // Datei muss vor neuem Anlegen entfernt werden, sonst meckert
            // transformToUri!
            if (file_exists($htmlFile)) unlink($htmlFile);

            $this->oHtmlFile = $htmlFile;
            $proc = new XSLTProcessor();
            $proc->importStylesheet(DOMDocument::load(dirname($xmlFile).DIRECTORY_SEPARATOR.self::$XSL_FILE));
            $proc->transformToURI(DOMDocument::load($xmlFile), 'file://'.$htmlFile);
            chmod($htmlFile, 0666);
            return true;
        }
        return false;
    }

    public function getHtmlFile()
    {
        return $this->oHtmlFile;
    }
}
?>
