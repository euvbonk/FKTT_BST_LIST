<?php

import('de_brb_hvl_wur_stumml_util_BasicDirectory');
import('de_brb_hvl_wur_stumml_util_table_Html');
import('de_brb_hvl_wur_stumml_util_table_TableRow');
import('de_brb_hvl_wur_stumml_util_table_TableCell');

import('de_brb_hvl_wur_stumml_datasheet_EntryRow');
import('de_brb_hvl_wur_stumml_datasheet_EntryRowImpl');

class TableContentEntries extends BasicDirectory
{
    private $rows;
    private $tableEntries;
    private $entryCount;
    
    public function __construct()
    {
        parent::__construct(StationDatasheetSettings::getInstance()->uploadDir());
        $this->rows = array();
        $this->tableEntries = "";
        $this->entryCount = 1;
    }

    public function buildTableEntries()
    {
		$array = self::scanDirectories($this->getDirName(), array("xml"));
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

    private function buildRelativePath($path)
    {
        global $rootDir;
        return substr($path, strlen($rootDir)+1);
    }

    private function addEntry(EntryRow $row)
    {
        $trow = new TableRow();
        $j = ($this->entryCount > 9) ? $this->entryCount : "0".$this->entryCount;
        $trow->addCell(new TableCell($j.".", 'class="mittig"'));
        $trow->addCell(new TableCell($this->getAbsoluteLink($row->getSheetUrl(), $row->getName())));
        $trow->addCell(new TableCell($row->getShort()));
        $trow->addCell(new TableCell($row->getType()));
        $trow->addCell(new TableCell($row->getLastChange()));
        $this->addRow($trow);
        $this->entryCount++;
    }

    private function getAbsoluteLink($url, $label)
    {
        return str_replace('index.php/', '', $this->convert(common::AbsoluteLink($url, $label), 1));
    }
    
	private function convert($str, $way)
	{
		$umlaute = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
		$ersetzt = array("&auml;", "&ouml;", "&uuml;", "&Auml;", "&Ouml;", "&Uuml;", "&szlig;");
		if ($way == 1)
        {
			return str_replace($umlaute, $ersetzt, $str);
		}
        elseif ($way == 2)
        {
			return str_replace($ersetzt, $umlaute, $str);
		}
        else
        {
            return FALSE;
        }
	}

    private function addRow(Html $row)
    {
        $this->tableEntries .= $row->getHtml();
    }
        
    public function echoEntries()
    {
        echo $this->tableEntries;
    }
}
?>
