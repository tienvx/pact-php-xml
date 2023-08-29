<?php

namespace Tienvx\PactPhpXml;

use AaronDDM\XMLBuilder\XMLElementData;
use Tienvx\PactPhpXml\Model\Matcher;

class XmlContent extends XmlElementData
{
    public function __construct(
        private Matcher $matcher,
        private mixed $content,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function getArray(): array
    {
        return [
            'content' => $this->content,
            'matcher' => $this->matcher->getArray(),
        ];
    }
}
