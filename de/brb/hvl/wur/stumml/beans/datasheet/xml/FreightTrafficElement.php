<?php
import('de_brb_hvl_wur_stumml_beans_datasheet_xml_ShipperElement');

class FreightTrafficElement
{
    private $oXml;
    private $oShippers = array();
    
    public function __construct(SimpleXMLElement $xml)
    {
        $this->oXml = $xml;
        foreach ($this->oXml->xpath("verlader") as $shipperEl)
        {
            $this->oShippers[] = new ShipperElement($shipperEl);
        }
    }

    public function getShippers()
    {
        return $this->oShippers;
    }

    public function getLoadingPlaceNameById($ids)
    {
        $str = "";
        foreach (explode(" ", $ids) as $id)
        {
            foreach ($this->oXml->xpath("ladestelle") as $lp)
            {
                if ($lp->attributes() == $id)
                {
                    if (strlen($str)>0)
                    {
                        $str .= ", ";
                    }
                    $str .= $lp->name;
                }
            }
        }
        return $str;
    }
}
?>
