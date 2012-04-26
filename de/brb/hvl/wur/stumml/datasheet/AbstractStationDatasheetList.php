<?php

import('de_brb_hvl_wur_stumml_Frame');
import('de_brb_hvl_wur_stumml_datasheet_StationDatasheetSettings');

import('de_brb_hvl_wur_stumml_datasheet_EntryRow');
import('de_brb_hvl_wur_stumml_datasheet_EntryRowImpl');

import('de_brb_hvl_wur_stumml_util_BasicDirectory');

import('de_brb_hvl_wur_stumml_html_Html');
import('de_brb_hvl_wur_stumml_html_table_TableRow');
import('de_brb_hvl_wur_stumml_html_table_TableCell');
import('de_brb_hvl_wur_stumml_html_util_HtmlUtil');

abstract class AbstractStationDatasheetList extends Frame
{
    private $entries;
    private $tableEntries;
    private $entryCount;
    private $currentCommand;
    public static $commands = array("ORDER_SHORT" => "orderShort", "ORDER_LAST" => "orderLast");

    public function __construct($command)
    {
        parent::__construct(StationDatasheetSettings::getInstance()->templateFile());
        $this->tableEntries = "";
        $this->entryCount = 1;
        if (in_array($command, array_keys(self::$commands)))
        {
            $this->currentCommand = self::$commands[$command];
        }
        else
        {
            throw new Exception("No such command -".$command."- for this module.");
        }
        $this->buildTableEntries($this->getOrderedArray($this->getXmlFilesAsArray()));
    }
    
    public abstract function getOrderedArray($array);

    public function getTable()
    {
        return $this->getTableHeader().$this->getTableEntries();
    }

    public function getFilterUI()
    {
        $ret = "Ordnen nach: ";
        if ($this->currentCommand == self::$commands['ORDER_SHORT'])
        {
            $ret .= "<span style='color:red;'>K&uuml;rzel (aufsteigend)</span>";
        }
        else
        {
            $ret .= common::Link(common::WhichPage(),'K&uuml;rzel (aufsteigend)','','title="Ordnen nach K&uuml;rzel (aufsteigend)"');
        }
        $ret .= "&nbsp;";
        if ($this->currentCommand == self::$commands['ORDER_LAST'])
        {
            $ret .= "<span style='color:red;'>letzte &Auml;nderung (absteigend)</span>";
        }
        else
        {
            $ret .= common::Link(common::WhichPage(),'letzte &Auml;nderung (absteigend)','cmd='.self::$commands['ORDER_LAST'],'title="Ordnen nach letzter &Auml;nderung (absteigend)"');
        }
        return $ret;
    }

    private function getXmlFilesAsArray()
    {
        return BasicDirectory::scanDirectories(StationDatasheetSettings::getInstance()->uploadDir(), array("xml"));
    }

    private function getTableEntries()
    {
        return $this->tableEntries;
    }

    private function buildRelativePath($path)
    {
        global $rootDir;
        return substr($path, strlen($rootDir)+1);
    }

    private function buildTableEntries($array)
    {
        if (!empty($array))
        {
            foreach($array as $value)
            {
                // Die Datei ist mit Sicherheit vom Typ XML!
                $xml = simplexml_load_file($value);
                $this->addEntry(
                    new EntryRowImpl(
                        (string)$xml->name,
                        $this->buildRelativePath($value),
                        (string)$xml->kuerzel,
                        (string)$xml->typ,
                        date("D, d. M Y H:i", filemtime($value))
                    )
                );
            }
        }
    }

    private function getAbsoluteLink($url, $label)
    {
        return str_replace('index.php/', '', HtmlUtil::toUtf8(common::AbsoluteLink($url, $label)));
    }

    private function addRow(Html $row)
    {
        $this->tableEntries .= $row->getHtml();
    }

    private function addEntry(EntryRow $row)
    {
        $trow = new TableRow();
        $j = ($this->entryCount > 9) ? $this->entryCount : "0".$this->entryCount;
        $trow->addCell(new TableCell($j.".", 'class="mittig"'));
        $trow->addCell(new TableCell($this->getAbsoluteLink($row->getSheetUrl(), $row->getName())));
        $trow->addCell(new TableCell($this->getAbsoluteLink($row->getSheetUrl(), $row->getShort())));
        $trow->addCell(new TableCell(HtmlUtil::toUtf8($row->getType())));
        $trow->addCell(new TableCell($row->getLastChange()));
        $this->addRow($trow);
        $this->entryCount++;
    }

    private function getTableHeader()
    {
        $trow = new TableRow();
        $trow->addCell(new TableCell("Lfd. Nr."));
        $trow->addCell(new TableCell("Betriebsstellenname"));
        $trow->addCell(new TableCell($this->buildCellContent('ORDER_SHORT', "K&uuml;rzel", "&#8593;")));
        $trow->addCell(new TableCell("Kategorie"));
        $trow->addCell(new TableCell($this->buildCellContent('ORDER_LAST', "Letzte &Auml;nderung", "&#8595;")));
        return $trow->getHtml();
    }

    private function buildCellContent($cmd, $main, $sub)
    {
        if ($this->currentCommand == self::$commands[$cmd])
        {
            return "<span style='color:red;'>".$main."&nbsp;".$sub."</span>";
        }
        else
        {
            return $main;
        }
    }
}
?>
