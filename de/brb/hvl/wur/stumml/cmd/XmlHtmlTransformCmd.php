<?php
namespace org\fktt\bstlist\cmd;

\import('de_brb_hvl_wur_stumml_io_File');

use DOMDocument;
use XSLTProcessor;
use org\fktt\bstlist\io\File;

final class XmlHtmlTransformCmd
{
    private $oHtmlFile;
    private $oXSLTProcessor;

    /**
     * @param File $xslFile
     * @return XmlHtmlTransformCmd
     */
    public function __construct(File $xslFile)
    {
        $this->oHtmlFile = null;

        $xslDOMDocument = new DOMDocument();
        $xslDOMDocument->load($xslFile->getPathname());

        $this->oXSLTProcessor = new XSLTProcessor();
        $this->oXSLTProcessor->importStylesheet($xslDOMDocument);
        return $this;
    }


    /**
     * @param File      $xmlFile
     * @param File $htmlFile [optional]
     * @return bool
     */
    public function doCommand(File $xmlFile, File $htmlFile = null)
    {
        // Umwandlung nur fuer xml Dateien sinnvoll und wenn Verzeichnis beschreibbar ist
        if (!$xmlFile->endsWith("xml") || !$xmlFile->getParentFile()->isWritable())
        {
            return false;
        }

        if ($htmlFile == null)
        {
            $htmlFile = new File($xmlFile->getParent()."/".$xmlFile->getBasename("xml")."html");
        }
        else
        {
            $this->oHtmlFile = $htmlFile;
        }
        if (!$htmlFile->exists() || $htmlFile->getMTime() < $xmlFile->getMTime())
        {
            // Datei muss vor neuem Anlegen entfernt werden, sonst meckert
            // transformToUri!
            if ($htmlFile->exists())
            {
                $htmlFile->delete();
            }
            $xmlDOMDocument = new DOMDocument();
            $xmlDOMDocument->load($xmlFile->getPathname());

            $this->oXSLTProcessor->transformToURI($xmlDOMDocument, 'file://'.$htmlFile->getPathname());

            $htmlFile->changeFileRights(0666);
            return true;
        }
        return false;
    }

    /**
     * @return File
     */
    public function getHtmlFile()
    {
        return $this->oHtmlFile;
    }
}
