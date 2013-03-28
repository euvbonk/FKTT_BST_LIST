<?php

defined('is_running') or die('Not an entry point...');

import('de_brb_hvl_wur_stumml_Settings');

class DTDDocumentationPage
{
    public function __construct()
    {
        $URL = Settings::getHttpUriForFile('xml/dtd_docu/index.html');
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