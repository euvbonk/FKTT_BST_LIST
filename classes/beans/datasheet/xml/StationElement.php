<?php
namespace org\fktt\bstlist\beans\datasheet\xml;

\import('beans_datasheet_xml_BaseElement');
\import('beans_datasheet_xml_FreightTrafficElement');

use SimpleXMLElement;

class StationElement extends BaseElement
{
    /**
     * @param SimpleXMLElement $xml
     * @return StationElement
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
    public function getShort()
    {
        return $this->getValueForTag('kuerzel');
    }

    /**
     * @return bool
     */
    public function hasFreightTraffic()
    {
        return (\count($this->getElement()->xpath("//bahnhof/gv")) > 0) ? true : false;
    }

    /**
     * @return FreightTrafficElement|null
     */
    public function getFreightTrafficElement()
    {
        $freight = $this->getElement()->xpath("//bahnhof/gv");
        return (\count($freight) > 0) ? new FreightTrafficElement($freight[0]) : null;
    }
}
