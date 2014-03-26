<?php
namespace org\fktt\bstlist\html\charset;

class LocaleCharacter
{
    static $DE = array("ä" => "&auml;",
                     "ö" => "&ouml;",
                     "ü" => "&uuml;",
                     "Ä" => "&Auml;",
                     "Ö" => "&Ouml;", 
                     "Ü" => "&Uuml;", 
                     "ß" => "&szlig;");
    static $PL = array(
                    "Ó" => "&#211;",
                    "ó" => "&#243;",
                    "Ą" => "&#260;",
                    "ą" => "&#261;",
                    "Ę" => "&#280;",
                    "ę" => "&#281;",
                    "Ć" => "&#262;",
                    "ć" => "&#263;",
                    "Ł" => "&#321;",
                    "ł" => "&#322;",
                    "Ń" => "&#323;",
                    "ń" => "&#324;",
                    "Ś" => "&#346;",
                    "ś" => "&#347;",
                    "Ź" => "&#377;",
                    "ź" => "&#378;",
                    "Ż" => "&#379;",
                    "ż" => "&#380;" 
                     );
    static $CZ = array(
                    "Á" => "&#193;",
                    "É" => "&#201;",
                    "Í" => "&#205;",
                    "Ó" => "&#211;",
                    "Ú" => "&#218;",
                    "Ý" => "&#221;",
                    "á" => "&#225;",
                    "é" => "&#233;",
                    "í" => "&#237;",
                    "ó" => "&#243;",
                    "ú" => "&#250;",
                    "ý" => "&#253;",
                    "Č" => "&#268;",
                    "Ď" => "&#270;",
                    "Ě" => "&#282;",
                    "Ň" => "&#327;",
                    "Ř" => "&#344;",
                    "Š" => "&#352;",
                    "Ů" => "&#366;",
                    "Ž" => "&#381;",
                    "č" => "&#269;",
                    "ď" => "&#271;",
                    "ě" => "&#283;",
                    "ň" => "&#328;",
                    "ř" => "&#345;",
                    "š" => "&#353;",
                    "ů" => "&#367;",
                    "ž" => "&#382;"
                    );

    public $MAP = array();

    private static $INSTANCE = null;

    private function __construct()
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        foreach (\get_class_vars(__CLASS__) as $key => $value)
        {
            if (\is_array($value) && !empty($value))
            {
                $this->MAP = \array_merge($this->MAP, $value);
            }
        }
    }

    /**
     * @static instance
     * @return LocaleCharacter
     */
    public static function getInstance()
    {
        if (null === self::$INSTANCE)
        {
            self::$INSTANCE = new self;
        }
        return self::$INSTANCE;
    }
}
