<?php
namespace org\fktt\bstlist\cmd;

\import('io_File');

use DOMDocument;
use XSLTProcessor;
use org\fktt\bstlist\io\File;

final class XmlHtmlTransformCmd
{
    private $oXmlDOMDocument;
    private $oXslDOMDocument;
    private $oXSLTProcessor;

    /**
     * @return XmlHtmlTransformCmd
     */
    public function __construct()
    {
        $this->oXmlDOMDocument = new DOMDocument();
        $this->oXslDOMDocument = new DOMDocument();

        $this->oXSLTProcessor = new XSLTProcessor();
        return $this;
    }

    /**
     * @param File $xslFile
     * @param File $xmlFile
     * @return bool|string
     */
    public function doCommand(File $xslFile, File $xmlFile)
    {
        // Umwandlung nur fuer xml Dateien sinnvoll
        if (!$xmlFile->endsWith("xml"))
        {
            return false;
        }

        // XML Datei laden
        $this->oXmlDOMDocument->load($xmlFile->getPathname());
        // XSL Datei laden
        $this->oXslDOMDocument->load($xslFile->getPathname());
        // XSL Datei in Prozessor laden
        $this->oXSLTProcessor->importStylesheet($this->oXslDOMDocument);
        // Umwandlung durchfuehren und HTML String zurueckliefern
        return $this->oXSLTProcessor->transformToDoc($this->oXmlDOMDocument)->saveHTML();
    }
}
