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

/*class YellowPageElement
{
    private $oXml = null;
    
    public function __construct($xmlFile)
    {
        $this->oXml = new SimpleXMLElement($xmlFile, null, true);
    }

    public function getName()
    {
        return (string)$this->oXml->name;
    }

    public function hasFreightTraffic()
    {
        print count($this->oXml->xpath("//bahnhof/gv"));
        return (count($this->oXml->xpath("//bahnhof/gv")) > 0) ? true : false;
    }

    public function getShippers()
    {
        //print_r($this->oXml->xpath("//bahnhof/gv/verlader/name"));
        return $this->oXml->xpath("//bahnhof/gv/verlader");
    }

    public function getDistributedCargosFromShipper($name)
    {
        foreach ($this->getShippers() as $shipper)
        {
            if ($shipper->name == $name)
            {
                return $shipper->xpath("versand");
            }
        }
    }
}*/
?>
