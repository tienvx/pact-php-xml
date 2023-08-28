<?php

namespace Tienvx\PactPhpXml;

use AaronDDM\XMLBuilder\XMLElementData as BaseXmlElementData;
use Tienvx\PactPhpXml\Exception\XmlElementTypeNotSupportedException;

class XmlElementData extends BaseXmlElementData
{
    private bool $matching = false;
    /**
     * @var array<string, mixed> $options
     */
    private array $options = ['examples' => 1];

    /**
     * @return array<string, mixed>
     */
    public function getArray(): array
    {
        if ($this->matching) {
            return [
                'pact:matcher:type' => 'type',
                'value' => $this->getCustomArray(),
                'examples' => $this->options['examples'] ?? 1,
            ];
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

    public function setMatching(bool $matching): XmlElementData
    {
        $this->matching = $matching;

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options): XmlElementData
    {
        $this->options = $options;

        return $this;
    }
}
