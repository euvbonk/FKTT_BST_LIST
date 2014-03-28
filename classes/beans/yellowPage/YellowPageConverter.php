<?php
namespace org\fktt\bstlist\beans\yellowpage;

interface YellowPageConverter
{
    public function setYellowPage(/*List<YellowPageTableRow>*/ $yellowPage);
    public function getYellowPage();
    public function generate();
}
