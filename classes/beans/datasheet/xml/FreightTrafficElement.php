<?php
namespace org\fktt\bstlist\beans\datasheet\xml;

\import('beans_datasheet_xml_BaseElement');
\import('beans_datasheet_xml_ShipperElement');

use SimpleXMLElement;

class FreightTrafficElement extends BaseElement
{
    private $oShippers = array();

    /**
     * @param SimpleXMLElement $xml
     * @return FreightTrafficElement
     */
    public function __construct(SimpleXMLElement $xml)
    {
        parent::__construct($xml);
        foreach ($this->getElement()->xpath("verlader") as $shipperEl)
        {
            $this->oShippers[] = new ShipperElement($shipperEl);
        }
        return $this;
    }

    /**
     * @return array ShipperElement
     */
    public function getShippers()
    {
        return $this->oShippers;
    }

    /**
     * @param $ids string
     * @return string
     */
    public function getLoadingPlaceNameById($ids)
    {
        $str = "";
        foreach (\explode(" ", $ids) as $id)
        {
            /** @var $lp SimpleXMLElement */
            foreach ($this->getElement()->xpath("ladestelle") as $lp)
            {
                //print "<pre>".print_r($lp->attributes(), true)."</pre>";
                foreach ($lp->attributes() as $value)
                {
                    if ($value == $id)
                    {
                        if (\strlen($str) > 0)
                        {
                            $str .= ", ";
                        }
                        /** @var $child SimpleXMLElement */
                        foreach ($lp->children() as $child)
                        {
                            if ($child->getName() == 'name')
                            {
                                $str .= $child[0];
                            }
                        }
                    }
                }
            }
        }
        return $str;
    }
}
