<?php

import('de_brb_hvl_wur_stumml_util_logging_AbstractLogger');

final class StdoutLogger extends AbstractLogger
{
    public function __construct($className)
    {
        parent::__construct($className);
    }

    protected function writeMessage($message)
    {
        print($message."<br/>");
    }
}
?>
