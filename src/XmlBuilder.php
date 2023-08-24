<?php

namespace Tienvx\PactPhpXml;

class XmlBuilder
{
    private XMLArray $xmlArray;

    public function __construct(
        private string $version,
        private string $charset,
        ?XMLArray $xmlArray = null
    ) {
        $this->xmlArray = $xmlArray ?? XmlArray::initiate(XmlElementData::class);
    }

    public function start(string $rootName, array $attributes = []): XMLArray
    {
        return $this->xmlArray->start($rootName, $attributes);
    }

    public function getArray(): array
    {
        return [
            'version' => $this->version,
            'charset' => $this->charset,
            'root' => $this->xmlArray->getArray(),
        ];
    }
}
