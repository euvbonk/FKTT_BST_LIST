<?php
import('de_brb_hvl_wur_stumml_io_File');

final class XmlHtmlTransformCmd
{
    private static $XSL_FILE = "bahnhof.xsl";
    private $oHtmlFile;

    public function __construct()
    {
        $this->oHtmlFile = null;
    }

    public function doCommand(File $xmlFile)
    {
        // Umwandlung nur fuer xml Dateien sinnvoll und wenn Verzeichnis beschreibbar ist
        if (!$xmlFile->endsWith("xml") || !$xmlFile->getParentFile()->isWritable())
        {
            return false;
        }

        $this->oHtmlFile = null;
        $htmlFile = new File($xmlFile->getParent()."/".basename($xmlFile->getPath(), "xml")."html");
        if (!$htmlFile->exists() || $htmlFile->compareMTimeTo($xmlFile))
        {
            // Datei muss vor neuem Anlegen entfernt werden, sonst meckert
            // transformToUri!
            if ($htmlFile->exists())
            {
                $htmlFile->delete();
            }

            $this->oHtmlFile = $htmlFile;

            $xslDOMDocument = new DOMDocument();
            $xslDOMDocument->load($xmlFile->getParent()."/".self::$XSL_FILE);

            $xmlDOMDocument = new DOMDocument();
            $xmlDOMDocument->load($xmlFile->getPath());

            $xsltProcessor = new XSLTProcessor();
            $xsltProcessor->importStylesheet($xslDOMDocument);
            $xsltProcessor->transformToURI($xmlDOMDocument, 'file://'.$htmlFile->getPath());

            $htmlFile->changeFileRights(0666);
            return true;
        }
        return false;
    }

    public function getHtmlFile()
    {
        return $this->oHtmlFile;
    }
}
