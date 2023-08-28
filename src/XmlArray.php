<?php

namespace Tienvx\PactPhpXml;

use AaronDDM\XMLBuilder\XMLArray as BaseXmlArray;

class XmlArray extends BaseXmlArray
{
    /**
     * @param array<string, mixed> $attributes
     * @param array<string, mixed> $options
     */
    public function eachLike(string $rootName, array $attributes = [], array $options = ['examples' => 1]): XmlArray
    {
        /** @var XmlArray */
        $root = self::initiate($this->getElementDataClass());
        $root->parent = $this;

        $element = $this->getElementDataClass()::create($rootName, $root, $attributes);
        $element->setMatching(true);
        $element->setOptions($options);

        $this->stack[] = $element;

        return $root;
    }
}
