<?php

import('de_brb_hvl_wur_stumml_Settings');
import('de_brb_hvl_wur_stumml_io_File');
import('de_brb_hvl_wur_stumml_cmd_SendFileForDownloadCmd');
import('de_brb_hvl_wur_stumml_pages_datasheet_SingleDatasheetCommandPage');

class EditDatasheet extends SingleDatasheetCommandPage
{
    private static $JNLP_FILE_NAME = "editor.jnlp";

    /**
     * @param String $station
     * @throws Exception
     * @return EditDatasheet
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
        /** @var $xml SimpleXMLElement */
        $xml = simplexml_load_file(Settings::uploadBaseDir()."/rgzm/".self::$JNLP_FILE_NAME);
        $foo = $xml->xpath("/jnlp/application-desc");
        /** @var $foo SimpleXMLElement */
        $foo = $foo[0];
        // add argument child element with given http URI to remote file
        $foo->addChild("argument", Settings::getHttpUriForFile($file->getPathname()));
        $s = new SendFileForDownloadCmd($xml->asXML(), "editor.jnlp");
        // force local changed xml from jnlp to download aka open with
        if ($s->doCommand())
        {
            exit;
        }
    }
}