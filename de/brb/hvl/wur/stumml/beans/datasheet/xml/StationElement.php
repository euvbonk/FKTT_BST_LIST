<?php
import('de_brb_hvl_wur_stumml_beans_datasheet_xml_FreightTrafficElement');

class StationElement
{
    private $oXml;
    
    public function __construct(SimpleXMLElement $xml)
    {
        $this->oXml = $xml;
    }

    public function getName()
    {
        return $this->oXml->name;
    }

    public function hasFreightTraffic()
    {
        return (count($this->oXml->xpath("//bahnhof/gv")) > 0) ? true : false;
    }

    public function getFreightTrafficElement()
    {
        $freight = $this->oXml->xpath("//bahnhof/gv");
        return (count($freight) > 0) ? new FreightTrafficElement($freight[0]) : null;
    }
}
?>
