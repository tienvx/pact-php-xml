<?php

namespace Tienvx\PactPhpXml;

use AaronDDM\XMLBuilder\XMLArray as BaseXmlArray;

class XmlArray extends BaseXmlArray
{
    public function eachLike(string $rootName, array $attributes = [], array $options = ['examples' => 1]): XmlArray
    {
        $root = self::initiate($this->getElementDataClass());
        $root->parent = $this;

        $element = $this->getElementDataClass()::create($rootName, $root, $attributes);
        $element->setMatching(true);
        $element->setOptions($options);

        $this->stack[] = $element;

        return $root;
    }
}
