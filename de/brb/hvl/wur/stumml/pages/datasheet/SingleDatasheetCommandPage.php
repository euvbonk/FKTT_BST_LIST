<?php
namespace org\fktt\bstlist\pages\datasheet;

\import('de_brb_hvl_wur_stumml_beans_datasheet_FileManagerImpl');
\import('de_brb_hvl_wur_stumml_io_File');
\import('de_brb_hvl_wur_stumml_pages_Frame');

use Exception;
use InvalidArgumentException;
use org\fktt\bstlist\pages\Frame;
use org\fktt\bstlist\io\File;
use org\fktt\bstlist\beans\datasheet\FileManagerImpl;

/**
 * Base class who delegates the action of one datasheet to a special class
 */
abstract class SingleDatasheetCommandPage extends Frame
{
    /**
     * @param String $station
     * @throws InvalidArgumentException
     * @throws Exception
     * @return SingleDatasheetCommandPage
     */
    public function __construct($station)
    {
        parent::__construct();

        if ($station == "")
        {
            throw new InvalidArgumentException("Kein Kommando angegeben oder Kommando fehlerhaft!");
        }
        else
        {
            $values = \explode("-", $station);
            $short = $values[0];
            if (\sizeof($values) > 1)
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

            if (!\array_key_exists($station, $allFiles))
            {
                throw new Exception("Angegebenes Datenblatt existiert nicht!");
            }
            else
            {
                $this->doIt($allFiles[$station], $short);
            }
        }
        return $this;
    }

    protected function getCallableMethods()
    {
        return array();
    }

    /**
     * @abstract
     * @param File   $file
     * @param String $short
     * @return void
     * @throws Exception
     */
    protected abstract function doIt(File $file, $short);
}