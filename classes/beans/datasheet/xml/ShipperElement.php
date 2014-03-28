<?php
namespace org\fktt\bstlist\beans\datasheet\xml;

\import('beans_datasheet_xml_BaseElement');
\import('beans_datasheet_xml_CargoElement');

use SimpleXMLElement;

class ShipperElement extends BaseElement
{
    private $oDistributedCargos = array();
    private $oReceivedCargos = array();

    /**
     * @param SimpleXMLElement $xml
     * @return ShipperElement
     */
    public function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml);
        foreach ($this->getElement()->xpath("versand/ladegut") as $cargoEl)
        {
            $this->oDistributedCargos[] = new CargoElement($cargoEl);
        }
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
     * @return array CargoElement
     */
    public function getDistributedCargos()
    {
        return $this->oDistributedCargos;
    }

    /**
     * @return array CargoElement
     */
    public function getReceivedCargos()
    {
        return $this->oReceivedCargos;
    }
}
