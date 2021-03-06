<?php
namespace org\fktt\bstlist\beans\tableList\datasheet;

\import('beans_datasheet_xml_BaseElement');
\import('beans_tableList_AbstractTableList');
\import('beans_tableList_datasheet_DatasheetListRowDataImpl');
\import('io_GlobIterator');
\import('util_reportTable_ListRow');
\import('util_reportTable_ListRowCellsImpl');

use SimpleXMLElement;
use org\fktt\bstlist\beans\datasheet\xml\BaseElement;
use org\fktt\bstlist\beans\tableList\AbstractTableList;
use org\fktt\bstlist\io\File;
use org\fktt\bstlist\io\GlobIterator;
use org\fktt\bstlist\util\reportTable\ListRow;
use org\fktt\bstlist\util\reportTable\ListRowCells;
use org\fktt\bstlist\util\reportTable\ListRowCellsImpl;

abstract class AbstractDatasheetList extends AbstractTableList
{
    private $tableEntries;
    private static $HEAD_ENTRIES = array("Lfd. Nr.", "Betriebsstellenname", "K&uuml;rzel", "Kategorie",
        "Letzte &Auml;nderung", "Spezial Ansicht");
    private $headOrder = array("", "", "", "", "", "");
    private $oLang;

    /**
     * @param string $order [optional]
     * @return AbstractDatasheetList
     */
    public function __construct($order = "ORDER_SHORT")
    {
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
            case "ORDER_NAME" :
                $this->headOrder[1] = "ASC";
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
     * @param array $lang
     * @return ListRowCells
     */
    protected abstract function getListRowImpl(DatasheetListRowData $data, array $lang = null);

    /**
     * @param $array
     */
    public function buildTableEntries($array)
    {
        $this->oLang = $this->getAvail();
        $this->tableEntries = new ListRow();
        if (!empty($array))
        {
            $key = 0;
            /** @var $value File */
            foreach ($array as $value)
            {
                // Die Datei ist mit Sicherheit vom Typ XML!
                $xml = new BaseElement(new SimpleXMLElement($value->getPathname(), null, true));
                $this->tableEntries->append($this->getListRowImpl(new DatasheetListRowDataImpl(($key+1), $xml, $value), $this->oLang));
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
        if (\strlen($this->headOrder[$cmd]) == 0)
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

    protected function getAvail()
    {
        $ret = array();
        $f = new File("./db/bahnhof.xsl");
        $it = new GlobIterator($f->getPath()."/bahnho*.xsl");
        $it->setInfoClass('org\fktt\bstlist\io\File');
        /** @var $file File */
        foreach ($it as $file)
        {
            $n = $file->getBasename(".xsl");
            $a = \explode("_", $n);
            if (!isset($a[1]))
            {
                $a = "DE";
            }
            else if ($a[1] != 'tpl')
            {
                $a = \strtoupper($a[1]);
            }
            if (\is_string($a) && \strlen($a) == 2)
            {
                $ret[] = $a;
            }
        }
        return $ret;
    }
}