<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_beans_datasheet_FileManagerImpl');
import('de_brb_hvl_wur_stumml_pages_Frame');

class FplView extends Frame
{
    private $content;
    
    public function __construct($station)
    {
        parent::__construct(null);

        if ($station == "")
        {
            throw new InvalidArgumentException("Kein Kommando angegeben oder Kommando fehlerhaft!");
        }
        else
        {
            $values = explode("-", $station);
            $short = $values[0];
            if (sizeof($values) > 1)
            {
                $epoch = $values[1];
            }
            else
            {
                $epoch = "IV";
            }

            $fm = new FileManagerImpl();
            $allFiles = $fm->getFilesFromEpochWithOrder($epoch);
            //print "<pre>".print_r($allFiles, true)."</pre>";

            if (!array_key_exists($station, $allFiles))
            {
                throw new Exception("Angegebenes Datenblatt existiert nicht!");
            }
            else
            {
                $xmlFile = $allFiles[$station];
                $this->content = file_get_contents(str_replace(".xml", "_fpl.html", $xmlFile->getPathname()));

                // Pfade fuer css und img anpassen!
                $basePath = dirname(Settings::getHttpUriForFile($xmlFile->getPathname()));
                $this->content = str_replace("bahnhof.css", $basePath."/bahnhof.css", $this->content);
                $this->content = str_replace("img src=\"".$short, "img src=\"".$basePath."/".$short, $this->content);
                $this->showContent();
            }
        }
    }
    
    //@Override
    public function showContent()
    {
        print $this->content;
        exit;
    }

    //@Override
    public function getLastChangeTimestamp()
    {
        // never used and reached!
        return "";
    }
}
