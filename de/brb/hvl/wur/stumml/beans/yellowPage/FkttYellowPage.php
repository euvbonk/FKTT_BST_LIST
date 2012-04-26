<?php

import('de_brb_hvl_wur_stumml_beans_yellowPage_AbstractYellowPage');
import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageTableRow');
import('de_brb_hvl_wur_stumml_beans_yellowPage_YellowPageTableRowCell');

class FkttYellowPage extends AbstractYellowPage
{
    public function __construct($fileList = array())
    {
        parent::__construct($fileList);
    }

    public function generate()
    {
        /*print "<table border=\"1\">
                   <tr>
                       <td>Kategorie</td>
                       <td>Produkte</td>
                       <td>Versender</td>
                       <td>Wagengattung</td>
                       <td>Betriebsstelle</td>
                       <td>Ladestelle</td>
                       <td>Besonderheiten</td>
                   </tr>\n";*/
        foreach ($this->getDatasheets() as $station)
        {
            $freight = $station->getFreightTrafficElement();
            if ($freight != null)
            {
                foreach ($freight->getShippers() as $shipper)
                {
                    if (count($shipper->getDistributedCargos())>0)
                    {
                        foreach ($shipper->getDistributedCargos() as $cargo)
                        {
                            //print "<tr><td>&nbsp;</td><td>".$cargo->getName()."</td><td>".$shipper->getName()."</td><td>".$cargo->getClassOfCar()."</td><td>".$station->getName()."</td><td>".$freight->getLoadingPlaceNameById($cargo->getLoadingPlaceIdentifier())."</td><td>&nbsp;</td></tr>\n";
                            $drow = new YellowPageTableRow();
                            $drow->addCell(new YellowPageTableRowCell(""));
                            $drow->addCell(new YellowPageTableRowCell($cargo->getName()));
                            $drow->addCell(new YellowPageTableRowCell($shipper->getName()));
                            $drow->addCell(new YellowPageTableRowCell($cargo->getClassOfCar()));
                            $drow->addCell(new YellowPageTableRowCell($station->getName()));
                            $drow->addCell(new YellowPageTableRowCell($freight->getLoadingPlaceNameById($cargo->getLoadingPlaceIdentifier())));
                            $drow->addCell(new YellowPageTableRowCell(""));
                            $this->add($drow);
                        }
                    }
                    /*if (count($shipper->getReceivedCargos())>0)
                    {
                        foreach ($shipper->getReceivedCargos() as $cargo)
                        {
                            //print "<tr><td>".$shipper->getName()."</td><td>".$cargo->getName()."</td><td>E</td><td>&nbsp;</td><td>".$cargo->getClassOfCar()."</td><td>".$station->getName()."</td><td>TODO</td></tr>\n";
                            $drow = new YellowPageTableRow();
                            $drow->addCell(new YellowPageTableRowCell($shipper->getName()));
                            $drow->addCell(new YellowPageTableRowCell($cargo->getName()));
                            $drow->addCell(new YellowPageTableRowCell("E"));
                            $drow->addCell(new YellowPageTableRowCell(""));
                            $drow->addCell(new YellowPageTableRowCell($cargo->getClassOfCar()));
                            $drow->addCell(new YellowPageTableRowCell($station->getName()));
                            $drow->addCell(new YellowPageTableRowCell());
                            $this->add($drow);
                        }
                    }*/
                }
            }
        }
        //print "</table>";
    }
}
?>
