<?php

namespace Tienvx\PactPhpXml;

use AaronDDM\XMLBuilder\XMLArray as BaseXmlArray;
use Tienvx\PactPhpXml\Model\Matcher;
use Tienvx\PactPhpXml\Model\Options;

class XmlArray extends BaseXmlArray
{
    /**
     * @param array<string, mixed> $attributes
     * @param Options|null $options
     */
    public function eachLike(string $rootName, array $attributes = [], ?Options $options = null): XmlArray
    {
        /** @var XmlArray */
        $root = self::initiate($this->getElementDataClass());
        $root->parent = $this;

        $element = $this->getElementDataClass()::create($rootName, $root, $attributes);
        $element->setMatcher(new Matcher());
        $element->setOptions($options ?? new Options());

        $this->stack[] = $element;

        return $root;
    }

    public function contentLike(mixed $content): XmlArray
    {
        $this->stack[] = new XmlContent(new Matcher(), $content);

        return $this;
    }
}
