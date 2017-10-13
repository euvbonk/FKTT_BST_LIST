<?php
namespace org\fktt\bstlist\pages\admin;

\import('beans_datasheet_FileManagerImpl');
\import('beans_datasheet_xml_BaseElement');
\import('io_File');
\import('pages_Frame');
\import('pages_FrameForm');
\import('util_QI');

use SimpleXMLElement;
use org\fktt\bstlist\beans\datasheet\FileManagerImpl;
use org\fktt\bstlist\beans\datasheet\xml\BaseElement;
use org\fktt\bstlist\io\File;
use org\fktt\bstlist\pages\Frame;
use org\fktt\bstlist\pages\FrameForm;
use org\fktt\bstlist\util\QI;

final class CreateEmptyDummySheet extends Frame implements FrameForm
{
    private $oMessage = null;

    public function __construct()
    {
        parent::__construct();
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && QI::getCommand() == "create_dummy" && \sizeof($_POST) > 3)
        {
            $sheet = new File("db/".\array_shift($_POST['countryCodes'])."/".\strtolower($_POST['sheet_short']).".xml");
            $dummy = new File("db/dummy.tpl");
            $el = new BaseElement(new SimpleXMLElement($dummy->getPathname(), null, true));
            $el->setValueForTag("name", $_POST['sheet_name']);
            $el->setValueForTag("kuerzel", $_POST['sheet_short']);
            $el->getElement()->asXML($sheet->getPathname());
            $this->oMessage .= "Station Data Sheet \"{$_POST['sheet_name']} ({$_POST['sheet_short']})\" successful created.";
        }
        return $this;
    }

    public function showContent()
    {
        $str = "<h3>Creates an empty dummy station data sheet:</h3>";
        $str .= "<h4><span style=\"font-weight:bold;text-decoration:underline;\">Attention:</span> This method does not perform any checks!</h4>";
        $str .= "<p>The following folders are available:";
        $str .= "<form action=\"".$this->FormActionUri()."\" method=\"post\">";
        $str .= "<ul style=\"list-style-type:none;\">";
        foreach ((new FileManagerImpl())->getAllCountryCodes() as $country)
        {
            $str .= "<li><label><input type=\"checkbox\" name=\"countryCodes[]\" value=\"".$country."\" style=\"vertical-align:middle;\"/>";
            $str .= "&thinsp;<span style=\"vertical-align:middle;\">".\strtoupper($country)."</span></label></li>";
        }
        $str .= "</ul>";
        $str .= "<table>";
        $str .= "<tr><td style=\"text-align: right;vertical-align: middle;\">Station name:</td><td><input type=\"text\" name=\"sheet_name\" value=\"\" size=\"20\" /></td></tr>";
        $str .= "<tr><td style=\"text-align: right;vertical-align: middle;\">Station short:</td><td><input type=\"text\" name=\"sheet_short\" value=\"\" size=\"10\" /></td></tr>";
        $str .= "<tr><td><input type=\"hidden\" name=\"cmd\" value=\"create_dummy\" /></td>";
        $str .= "<td><input type=\"submit\" value=\"Create data sheet\" /></td></tr>";
        $str .= "</table>";
        $str .= "</form>";
        $str .= "</p>";
        if ($this->oMessage != null)
        {
            $str .= "<p style=\"color: red\">".$this->oMessage."</p>";
        }
        echo $str;
    }

    protected function getCallableMethods()
    {
        return array();
    }

    /**
     * @see Interface FrameForm
     */
    public final function FormActionUri()
    {
        return QI::getPageUri();
    }
}
