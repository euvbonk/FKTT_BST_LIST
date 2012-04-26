<?php
class CargoElement
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

    public function getClassOfCar()
    {
        return $this->oXml->gattung;
    }

    public function getLoadingPlaceIdentifier()
    {
        return (string)$this->oXml->attributes()->ladestelle;
    }
}
?>
