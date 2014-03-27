<?php
namespace org\fktt\bstlist\io;

\import('de_brb_hvl_wur_stumml_io_File');

use OutOfBoundsException;
use FilesystemIterator;
use Countable;
use Iterator;
use SeekableIterator;
use Traversable;

class GlobIterator extends FilesystemIterator implements Traversable, Iterator, SeekableIterator, Countable
{
    private $oCount = 0;
    private $oArray = array();

    public function __construct($path)
    {
        foreach (\glob($path) as $file)
        {
            $this->oArray[] = new File($file);
            $this->next();
        }
    }

    public function count()
    {
        return $this->oCount;
    }

    public function rewind()
    {
        $this->oCount = 0;
    }

    public function current()
    {
        return $this->oArray[$this->oCount];
    }

    public function key()
    {
        return $this->oCount;
    }

    public function next()
    {
        ++$this->oCount;
    }

    public function valid()
    {
        return isset($this->oArray[$this->oCount]);
    }

    public function seek($position)
    {
        if (!isset($this->oArray[$position]))
        {
            throw new OutOfBoundsException("invalid seek position ($position)");
        }
        $this->oCount = $position;
    }
}