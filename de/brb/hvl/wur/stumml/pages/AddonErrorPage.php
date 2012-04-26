<?php

import('de_brb_hvl_wur_stumml_pages_Frame');

class AddonErrorPage extends Frame
{
    private $cMessage;

    public function __construct($message)
    {
        parent::__construct(null);
        $this->cMessage = $message;
    }

    public function getLastChangeTimestamp()
    {
    }

    //@Override
    public function showContent()
    {
        print "<h2>Fehler!</h2>";
        print "<p>".$this->cMessage."</p>";
    }
}

?>
