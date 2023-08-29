<?php

namespace Tienvx\PactPhpXml\Model;

class Options
{
    public function __construct(private int $examples = 1)
    {
    }

    /**
     * @return array<string, int>
     */
    public function getArray(): array
    {
        return ['examples' => $this->examples];
    }
}
