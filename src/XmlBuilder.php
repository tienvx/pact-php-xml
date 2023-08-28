<?php

namespace Tienvx\PactPhpXml;

class XmlBuilder
{
    private XmlArray $xmlArray;

    public function __construct(
        private string $version,
        private string $charset,
        ?XmlArray $xmlArray = null
    ) {
        $this->xmlArray = $xmlArray ?? XmlArray::initiate(XmlElementData::class); // @phpstan-ignore-line
    }

    /**
     * @param array<string, string> $attributes
     */
    public function start(string $rootName, array $attributes = []): XmlArray
    {
        /** @var XmlArray */
        return $this->xmlArray->start($rootName, $attributes);
    }

    /**
     * @return array<string, mixed>
     */
    public function getArray(): array
    {
        return [
            'version' => $this->version,
            'charset' => $this->charset,
            'root' => $this->xmlArray->getArray(),
        ];
    }
}
