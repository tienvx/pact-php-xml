<?php

namespace Tienvx\PactPhpXml\Model;

class Matcher
{
    public function __construct(private string $matcher = 'type', private ?string $generator = null)
    {
    }

    /**
     * @return array<string, string>
     */
    public function getArray(): array
    {
        return ['pact:matcher:type' => $this->matcher] + ($this->generator ? ['pact:generator:type' => $this->generator] : []);
    }
}
