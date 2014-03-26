<?php
namespace org\fktt\bstlist\beans\datasheet\tableList\goodsTraffic;

import('de_brb_hvl_wur_stumml_beans_tableList_goodsTraffic_GoodsTrafficListRowData');
import('de_brb_hvl_wur_stumml_html_util_HtmlUtil');
import('de_brb_hvl_wur_stumml_util_reportTable_ListRowCells');
import('de_brb_hvl_wur_stumml_util_reportTable_ReportTableListProperties');
use org\fktt\bstlist\html\util\HtmlUtil;
use org\fktt\bstlist\util\reportTable\ListRowCells;
use org\fktt\bstlist\util\reportTable\ReportTableListProperties;

class GoodsTrafficListRowEntry implements ListRowCells
{
    private $oRowData;

    /**
     * @param GoodsTrafficListRowData $data
     * @return GoodsTrafficListRowEntry
     */
    public function __construct(GoodsTrafficListRowData $data)
    {
        $this->oRowData = $data;
        return $this;
    }

    /**
     * @return GoodsTrafficListRowData
     */
    protected function getData()
    {
        return $this->oRowData;
    }

    /**
     * @return array mixed
     */
    public function getCellsContent()
    {
        $xml = $this->getData()->getDatasheetElement();
        $name = $xml->getName();
        $nameRef = $this->getData()->getFile()->toDownloadLink($name, false);
        $short = $xml->getShort();
        $shortRef = $this->getData()->getFile()->toDownloadLink($short, false);
        return array(
                     HtmlUtil::toUtf8($short),
                     $shortRef,
                     $nameRef,
                     \sprintf(ReportTableListProperties::FORMAT, $xml->getCarsInput()),
                     \sprintf(ReportTableListProperties::FORMAT, $xml->getCarsOutput()),
                     \sprintf(ReportTableListProperties::FORMAT, $xml->getCarsMax()),
                     $xml->getShortestMainTrackLength(),
                     $xml->getLongestMainTrackLength()
                    );
    }

    /**
     * @return array string
     */
    public function getCellsStyle()
    {
        $s = "style=\"text-align:center;\"";
        return array("", $s, "", $s, $s, $s, $s, $s);
    }
}
