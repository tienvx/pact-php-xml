<?php

namespace Tienvx\PactPhpXml;

use Tienvx\PactPhpXml\Model\Builder\ElementTrait;
use Tienvx\PactPhpXml\Model\Builder\GeneratorTrait;
use Tienvx\PactPhpXml\Model\Builder\MatcherTrait;
use Tienvx\PactPhpXml\Model\Builder\TextTrait;

class XmlBuilder
{
    use ElementTrait;
    use TextTrait;
    use MatcherTrait;
    use GeneratorTrait;

    public function __construct(private string $version, private string $charset)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function getArray(): array
    {
        return [
            'version' => $this->version,
            'charset' => $this->charset,
            'root' => $this->root->getArray(),
        ];
    }
}
