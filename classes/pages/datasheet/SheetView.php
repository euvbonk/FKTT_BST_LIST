<?php
namespace org\fktt\bstlist\pages\datasheet;

\import('cmd_XmlHtmlTransformCmd');
\import('io_File');
\import('pages_datasheet_SingleDatasheetCommandPage');

use Exception;
use org\fktt\bstlist\cmd\XmlHtmlTransformCmd;
use org\fktt\bstlist\io\File;

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
        $cmd = new XmlHtmlTransformCmd();
        $this->content = $cmd->doCommand($this->oXslFile, $file);

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
