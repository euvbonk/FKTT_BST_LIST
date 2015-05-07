<?php
namespace org\fktt\bstlist\util;

use org\fktt\bstlist\io\File;

class PhpConfigFileUtils
{
    public static function putArrayToFile(File $file, array $array)
    {
        \file_put_contents($file->getPathname(), '<?php $array='.\var_export($array, true).';');
    }

    public static function getArrayFromFile(File $file)
    {
        // for getting all variables in included php file, there is a simple trick:
        // save all current defined variables
        $vars = \get_defined_vars();
        // include the file with the new defined variables
        include($file->getPathname());
        // save now all defined variables
        $vars2 = \get_defined_vars();
        // drop all before include variables in the after include array
        unset($vars2['vars']);
        // drop the file variable
        unset($vars2['file']);
        foreach($vars2 as $key => $value)
        {
           return $value;
        }
        // return the included config array
        return $vars2;
    }
}
