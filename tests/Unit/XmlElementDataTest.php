<?php

namespace Tienvx\PactPhpXml\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tienvx\PactPhpXml\Model\Matcher;
use Tienvx\PactPhpXml\Model\Options;
use Tienvx\PactPhpXml\XmlElementData;

class XmlElementDataTest extends TestCase
{
    public function testGetMatchingArray(): void
    {
        /** @var XmlElementData */
        $element = XmlElementData::create('Child', 'test', ['myAttr' => 'attr-value']);
        $element->setMatcher(new Matcher());
        $element->setOptions(new Options(examples: 7));

        $this->assertSame(<<<JSON
        {
            "pact:matcher:type": "type",
            "value": {
                "name": "Child",
                "children": "test",
                "attributes": {
                    "myAttr": "attr-value"
                }
            },
            "examples": 7
        }
        JSON, json_encode($element->getArray(), JSON_PRETTY_PRINT));
    }

    public function testGetCustomArray(): void
    {
        $element = XmlElementData::create('Child', 'test', ['myAttr' => 'attr-value']);

        $this->assertSame(<<<JSON
        {
            "name": "Child",
            "children": "test",
            "attributes": {
                "myAttr": "attr-value"
            }
        }
        JSON, json_encode($element->getArray(), JSON_PRETTY_PRINT));
    }
}
