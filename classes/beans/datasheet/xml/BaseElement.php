<?php
namespace org\fktt\bstlist\beans\datasheet\xml;

use SimpleXMLElement;

class BaseElement
{
    private $oXml;

    /**
     * @param SimpleXMLElement $xml
     * @return BaseElement
     */
    public function __construct(SimpleXMLElement $xml)
    {
        $this->oXml = $xml;
        return $this;
    }

    /**
     * @return SimpleXMLElement
     */
    public function getElement()
    {
        return $this->oXml;
    }

    /**
     * @param $tagName
     * @return string
     */
    public function getValueForTag($tagName)
    {
        /** @var $child SimpleXMLElement */
        foreach ($this->getElement()->children() as $child)
        {
            //print "<pre>".print_r($child, true)."</pre>";
            //print $child->getName()." => ".$tagName."<br>";
            if ($child->getName() == $tagName)
            {
                return (string)$child[0];
            }
        }
        return "";
    }

    /**
     * @param $attributeName
     * @return string
     */
    public function getValueForAttribute($attributeName)
    {
        foreach ($this->getElement()->attributes() as $name => $value)
        {
            if ($attributeName == $name)
            {
                return $value;
            }
        }
        return "";
    }

    /**
     * @param $tagName
     * @param $tagValue
     */
    public function setValueForTag($tagName, $tagValue)
    {
        /** @var $child SimpleXMLElement */
        foreach ($this->getElement()->children() as $child)
        {
            if ($child->getName() == $tagName)
            {
                $child[0] = $tagValue;
            }
        }
    }
}
