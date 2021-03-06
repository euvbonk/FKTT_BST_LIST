<?php

\defined('is_running') or die('Not an entry point...');

\import('de_brb_hvl_wur_stumml_io_File');
use org\fktt\bstlist\io\File;

class DTDDocumentationPage
{
    public function __construct()
    {
        $file = new File('xml/dtd_docu/index.html');
        $URL = $file->toHttpUrl();
        // Irgendwas
        echo '<iframe scrolling="auto" height="550px" width="95%" src="'.$URL.'">';
        echo '<p>Ihr Browser kann leider keine eingebetteten Frames anzeigen:';
        echo ' Sie k&ouml;nnen die eingebettete Seite &uuml;ber den folgenden Verweis';
        echo ' aufrufen: <a href="'.$URL.'">DTD-Dokumentation</a></p>';
        echo '</iframe>';
        return $this;
    }

    public function DTDDocumentationPage()
    {
        return self::__construct();
    }
}