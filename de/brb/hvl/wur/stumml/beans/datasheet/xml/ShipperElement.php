<?php
import('de_brb_hvl_wur_stumml_beans_datasheet_xml_CargoElement');

class ShipperElement
{
    private $oXml;
    private $oDistributedCargos = array();
    private $oReceivedCargos = array();

    public function __construct(SimpleXMLElement $xml)
    {
        $this->oXml = $xml;
        foreach ($this->oXml->xpath("versand/ladegut") as $cargoEl)
        {
            $this->oDistributedCargos[] = new CargoElement($cargoEl);
        }
    }

    public function getName()
    {
        return $this->oXml->name;
    }

    public function getDistributedCargos()
    {
        return $this->oDistributedCargos;
    }

    public function getReceivedCargos()
    {
        return $this->oReceivedCargos;
    }
}
?>
