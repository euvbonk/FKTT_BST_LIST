<?php
namespace org\fktt\bstlist\util\openOffice;

\import('de_brb_hvl_wur_stumml_util_openOffice_XCell');

use SimpleXMLElement;

/* class represents a spreadsheet table */
class XSpreadsheet
{
    private /*Array([string] => string,...)*/ $NameSpaces = null;
    private /*SimpleXMLElement*/ $oXml = null;

    /**
     * @param SimpleXMLElement $content
     * @param array string     $namespaces
     * @return XSpreadsheet
     */
    public function __construct(SimpleXMLElement $content, $namespaces)
    {
        $this->oXml = $content;
        $this->NameSpaces = $namespaces;
        return $this;
    }

    /**
     * @param int $xIndex
     * @param int $yIndex
     * @return XCell
     */
    public function getCellByPosition($xIndex, $yIndex)
    {
        //print "Position: x=>".$xIndex.":y=>".$yIndex."<br/>";
        // check if the rows and cells exists und if not, they will created
        $this->checkCellExists($xIndex, $yIndex);
        // xpath muss erneut ausgeführt werden, da sich der DOM geändert hat.
        $trows = $this->getXml()->xpath("table:table-row");
        $cellsInRow = $trows[$yIndex]->xpath("table:table-cell");
        return new XCell($cellsInRow[$xIndex], $this->NameSpaces);
    }

    /**
     * @return SimpleXMLElement
     */
    protected function getXml()
    {
        return $this->oXml;
    }

    /**
     * @param string $str
     */
    /** @noinspection PhpUnusedPrivateMethodInspection */
    private function printDebug($str)
    {
        print "<pre>";
        \var_dump($str);
        print "</pre>";
    }

    /**
     * @param int $cellIndex
     * @param int $rowIndex
     */
    private function checkCellExists($cellIndex, $rowIndex)
    {
        $rowsInTable = $this->getXml()->xpath("table:table-row");
        $ns_table = $this->NameSpaces['table'];
        if (!\in_array($rowIndex, \array_keys($rowsInTable)))
        {
            // Die Tabellenzeile mit dem angegebenen Index existiert
            // nicht und wird angelegt.
            $rowCount = \count($rowsInTable);
            //print "Die Tabellenzeile mit Index: ".$rowIndex." wird angelegt. (".($rowCount-1).")<br/>";
            for ($i = $rowCount; $i <= $rowIndex; $i++)
            {
                $this->getXml()->addChild("table:table-row", null, $ns_table);
                // add table row attribute
                $foo = $this->getXml()->children($ns_table);
                /** @var $newTableRow SimpleXMLElement */
                $newTableRow = $foo[$foo->count()-1];
                // das folgende Styleattriute ist speziell!!!
                $newTableRow->addAttribute("table:style-name", "ro2", $ns_table);
                // In einer Tabellenzeile, muss sich wenigstens eine Zelle befinden!
                $newTableRow->addChild("table:table-cell", null, $ns_table);
            }
        }
        // erneute Abfrage erforderlich, da sich DOM Baum geändert hat!!!
        $rowsInTable = $this->getXml()->xpath("table:table-row");
        $cellsInRow = $rowsInTable[$rowIndex]->xpath("table:table-cell");
        if (!\in_array($cellIndex, \array_keys($cellsInRow)))
        {
            // Die Tabellenzelle mit dem angegebenen Index existiert
            // nicht und wird angelegt
            $cellCount = \count($cellsInRow);
            //print "Die Tabellenzelle mit Index: ".$cellIndex." wird angelegt. (".($cellCount-1).")<br/>";
            for ($i = $cellCount; $i <= $cellIndex; $i++)
            {
                $rowsInTable[$rowIndex]->addChild("table:table-cell", null, $ns_table);
            }
        }
    }
}
