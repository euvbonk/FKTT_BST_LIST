<?php

import('de_brb_hvl_wur_stumml_beans_tableList_AbstractTableList');
import('de_brb_hvl_wur_stumml_beans_tableList_datasheet_DatasheetListRowDataImpl');
import('de_brb_hvl_wur_stumml_beans_datasheet_xml_BaseElement');

import('de_brb_hvl_wur_stumml_util_reportTable_ListRow');
import('de_brb_hvl_wur_stumml_util_reportTable_ListRowCellsImpl');

abstract class AbstractDatasheetList extends AbstractTableList
{
    private $tableEntries;
    private static $HEAD_ENTRIES = array("Lfd. Nr.", "Betriebsstellenname", "K&uuml;rzel", "Kategorie",
        "Letzte &Auml;nderung", "Spezial Ansicht", "Bearbeiten");
    private $headOrder = array("", "", "", "", "", "", "");
    private $isEditorPresent;

    /**
     * @param bool   $isEditorPresent
     * @param string $order [optional]
     * @return AbstractDatasheetList
     */
    public function __construct($isEditorPresent, $order = "ORDER_SHORT")
    {
        $this->isEditorPresent = $isEditorPresent;
        $this->setOrder($order);

        return $this;
    }

    /**
     * @param string $order [optional]
     */
    public function setOrder($order = "ORDER_SHORT")
    {
        switch ($order)
        {
            case "ORDER_SHORT" :
                $this->headOrder[2] = "ASC";
                break;
            case "ORDER_LAST"  :
                $this->headOrder[4] = "DESC";
                break;
            default            :
                $this->headOrder[2] = "ASC";
                break;
        }
    }

    /**
     * @return ListRow
     */
    public function getTableFooter()
    {
        return;
    }

    /**
     * @return ListRow
     */
    public function getTableEntries()
    {
        return $this->tableEntries;
    }

    /**
     * @param DatasheetListRowData $data
     * @return ListRowCells
     */
    protected abstract function getListRowImpl(DatasheetListRowData $data);

    /**
     * @param $array
     */
    public function buildTableEntries($array)
    {
        $this->tableEntries = new ListRow();
        if (!empty($array))
        {
            $key = 0;
            /** @var $value File */
            foreach ($array as $value)
            {
                // Die Datei ist mit Sicherheit vom Typ XML!
                $xml = new BaseElement(new SimpleXMLElement($value->getPathname(), null, true));
                $this->tableEntries->append($this->getListRowImpl(new DatasheetListRowDataImpl(($key+1), $xml, $value, $this->isEditorPresent)));
                $key++;
            }
        }
    }

    /**
     * @return ListRow
     */
    public function getTableHeader()
    {
        $cells = array();
        foreach (self::$HEAD_ENTRIES as $key => $value)
        {
            $cells[] = $this->buildCellContent($key, $value);
        }
        // Schade, dass man nicht folgendes schreiben kann:
        // return new ListRow()->append(new ListRowCellsImpl($cells));
        // sondern den Umweg über eine Variable gehen muss!
        $l = new ListRow();
        $l->append(new ListRowCellsImpl($cells));
        return $l;
    }

    /**
     * @param int    $cmd
     * @param string $main
     * @return string
     */
    private function buildCellContent($cmd, $main)
    {
        // wenn es für diesen Eintrag keine Order-Angabe gibt
        if (strlen($this->headOrder[$cmd]) == 0)
        {
            return $main;
        }

        $sub = "";
        if ($this->headOrder[$cmd] == "ASC")
        {
            $sub = "&#8593;";
        }
        else if ($this->headOrder[$cmd] == "DESC")
        {
            $sub = "&#8595;";
        }
        return "<span style='color:red;'>".$main."&nbsp;".$sub."</span>";
    }
}