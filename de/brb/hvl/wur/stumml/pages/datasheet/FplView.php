<?php

import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_pages_datasheet_SingleDatasheetCommandPage');

class FplView extends SingleDatasheetCommandPage
{
    private $content;

    /**
     * @param String $station
     * @throws Exception
     * @return FplView
     */
    public function __construct($station)
    {
        parent::__construct($station);
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
        $this->content = @file_get_contents(str_replace(".xml", "_fpl.html", $file->getPathname()));
        if ($this->content === false)
        {
            throw new Exception("&Ouml;ffnen der Datei fehlgeschlagen oder Datei existiert nicht!");
        }
        // Pfade fuer css und img anpassen!
        $basePath = dirname($file->toHttpUrl());
        $this->content = str_replace("bahnhof.css", $basePath."/bahnhof.css", $this->content);
        $this->content = str_replace("img src=\"".$short, "img src=\"".$basePath."/".$short, $this->content);
        $this->showContent();
    }

    //@Override
    public function showContent()
    {
        print $this->content;
        exit;
    }
}
