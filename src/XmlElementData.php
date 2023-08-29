<?php

namespace Tienvx\PactPhpXml;

use AaronDDM\XMLBuilder\XMLElementData as BaseXmlElementData;
use Tienvx\PactPhpXml\Exception\XmlElementTypeNotSupportedException;
use Tienvx\PactPhpXml\Model\Matcher;
use Tienvx\PactPhpXml\Model\Options;

class XmlElementData extends BaseXmlElementData
{
    private ?Matcher $matcher = null;

    private Options $options;

    /**
     * @return array<string, mixed>
     */
    public function getArray(): array
    {
        if ($this->matcher) {
            return $this->matcher->getArray() + [
                'value' => $this->getCustomArray(),
            ] + $this->options->getArray();
        }

        return $this->getCustomArray();
    }

    /**
     * @return array<string, mixed>
     */
    private function getCustomArray(): array
    {
        if ($this->getType() !== null) {
            throw new XmlElementTypeNotSupportedException(sprintf('Xml element type %s is not supported', $this->getType()));
        }

        return [
            'name' => $this->getName(),
            'children' => ($this->getValue() instanceof XmlArray) ? $this->getValue()->getArray() : $this->getValue(),
            'attributes' => (object) $this->getAttributes()
        ];
    }

    public function setMatcher(?Matcher $matcher): XmlElementData
    {
        $this->matcher = $matcher;

        return $this;
    }

    public function setOptions(Options $options): XmlElementData
    {
        $this->options = $options;

        return $this;
    }
}
