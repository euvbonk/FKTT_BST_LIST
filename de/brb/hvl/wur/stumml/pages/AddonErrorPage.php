<?php
namespace org\fktt\bstlist\pages;

import('de_brb_hvl_wur_stumml_pages_Frame');

class AddonErrorPage extends Frame
{
    private $cMessage;

    /**
     * @param String|null $message
     */
    public function __construct($message)
    {
        parent::__construct();
        $this->cMessage = $message;
    }

    protected function getCallableMethods()
    {
        return array();
    }

    /**
     * Shows given error message
     */
    //@Override
    public function showContent()
    {
        print "<h2>Fehler!</h2>";
        print "<p>".$this->cMessage."</p>";
    }
}
