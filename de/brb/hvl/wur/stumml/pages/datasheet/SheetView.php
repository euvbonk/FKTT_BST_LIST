<?php
namespace org\fktt\bstlist\pages\datasheet;

\import('de_brb_hvl_wur_stumml_io_File');
\import('de_brb_hvl_wur_stumml_pages_datasheet_SingleDatasheetCommandPage');

use Exception;
use org\fktt\bstlist\io\File;
use DOMDocument;
use XSLTProcessor;

class SheetView extends SingleDatasheetCommandPage
{
    private $content;
    private $oXslFile;

    /**
     * @param String $stationShort
     * @param String $lang
     * @param String $defaultXsl
     * @throws Exception
     * @return SheetView
     */
    public function __construct($stationShort, $defaultXsl, $lang = null)
    {
        if (!isset($defaultXsl) || $defaultXsl == null || $defaultXsl == "")
        {
            throw new Exception("No XSL file name given!");
        }
        $XslFileDefault = new File("db/".$defaultXsl.".xsl");
        if (!$XslFileDefault->exists()) throw new Exception("Given XSL file does not exist!");

        $XslFile = null;
        if ($lang != null && \is_string($lang) && \strlen($lang) >= 2 && \strtolower($lang) != 'de')
        {
            $XslFile = new File("db/".$defaultXsl."_".\strtolower($lang).".xsl");
            // catch non existing files to default
            if (!$XslFile->exists()) $XslFile = null;
        }
        $this->oXslFile = $XslFile != null ? $XslFile : $XslFileDefault;
        parent::__construct($stationShort);
        return $this;
    }

    /**
     * @abstract
     * @param File   $file
     * @param String $short
     * @return void
     * @throws Exception
     */
    //@Override
    protected function doIt(File $file, $short)
    {
        $xslDOMDocument = new DOMDocument();
        $xslDOMDocument->load($this->oXslFile->getPathname());

        $XSLTProcessor = new XSLTProcessor();
        $XSLTProcessor->importStylesheet($xslDOMDocument);

        $xmlDOMDocument = new DOMDocument();
        $xmlDOMDocument->load($file->getPathname());

        \ob_start();
        $XSLTProcessor->transformToURI($xmlDOMDocument, 'php://output');
        $this->content = \ob_get_contents();
        \ob_end_clean();

        // Adapt css and img file paths!
        $basePath = \dirname($file->toHttpUrl());
        $this->content = \str_replace("bahnhof.css", $basePath."/bahnhof.css", $this->content);
        $this->content = \str_replace("img src=\"".$short, "img src=\"".$basePath."/".$short, $this->content);
    }

    //@Override
    public function showContent()
    {
        print $this->content;
        exit;
    }
}
