<?php
namespace org\fktt\bstlist\util;

\import('beans_datasheet_xml_BaseElement');
\import('io_File');

use SimpleXMLElement;
use org\fktt\bstlist\beans\datasheet\xml\BaseElement;
use org\fktt\bstlist\io\File;

class SorterFactory
{
    private function __construct(){}

    /**
     * Compares two station datasheets by their name and returns
     *  0 if and only if the times of both files are equal
     * +1 if the time of the first given file is smaller
     * -1 if the time of the first given file is greater
     *
     * The function is used for sorting station datasheet files by name
     * ascending order (last modified at the top) using php function usort.
     *
     * @static
     * @param File $a
     * @param File $b
     * @return int
     */
    public static function compareStationName(File $a, File $b)
    {
        // Die Datei ist mit Sicherheit vom Typ XML!
        $station_a = new BaseElement(new SimpleXMLElement($a->getPathname(), null, true));
        $station_b = new BaseElement(new SimpleXMLElement($b->getPathname(), null, true));
        return \strcmp($station_a->getValueForTag('name'), $station_b->getValueForTag('name'));
    }
}
