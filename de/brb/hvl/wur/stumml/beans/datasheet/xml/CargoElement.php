<?php
namespace org\fktt\bstlist\beans\datasheet\xml;

\import('de_brb_hvl_wur_stumml_beans_datasheet_xml_BaseElement');

use SimpleXMLElement;

class CargoElement extends BaseElement
{
    /**
     * @param SimpleXMLElement $xml
     * @return CargoElement
     */
    public function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml);
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getValueForTag('name');
    }

    /**
     * @return string
     */
    public function getClassOfCar()
    {
        return $this->getValueForTag('gattung');
    }

    /**
     * @return string
     */
    public function getLoadingPlaceIdentifier()
    {
        //return (string)$this->getElement()->attributes()->ladestelle;
        return $this->getValueForAttribute('ladestelle');
    }
}
