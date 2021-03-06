<?php
namespace org\fktt\bstlist\util\logging;

\import('util_logging_AbstractLogger');

final class StdoutLogger extends AbstractLogger
{
    /**
     * @param string $className
     * @return StdoutLogger
     */
    public function __construct($className)
    {
        parent::__construct($className);
        return $this;
    }

    /**
     * @param string $message
     */
    protected function writeMessage($message)
    {
        print($message."<br/>");
    }
}
