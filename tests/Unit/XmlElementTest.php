<?php

namespace Tienvx\PactPhpXml\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tienvx\PactPhpXml\Model\Matcher\Generator;
use Tienvx\PactPhpXml\Model\Matcher\Matcher;
use Tienvx\PactPhpXml\XmlElement;

class XmlElementTest extends TestCase
{
    private XmlElement $element;

    public function setUp(): void
    {
        $this->element = new XmlElement();
        $this->element->setName('Child');
        $this->element->addAttribute('myAttr', 'attr-value');
    }

    public function testGetMatcherArray(): void
    {
        $this->element->setMatcher(new Matcher(
            fn (Matcher $matcher) => $matcher->setType('type'),
            fn (Matcher $matcher) => $matcher->setOptions(['examples' => 7]),
        ));

        $this->assertSame(<<<JSON
        {
            "value": {
                "name": "Child",
                "children": [],
                "attributes": {
                    "myAttr": "attr-value"
                }
            },
            "pact:matcher:type": "type",
            "examples": 7
        }
        JSON, json_encode($this->element->getArray(), JSON_PRETTY_PRINT));
    }

    public function testGetGeneratorArray(): void
    {
        $this->element->setMatcher(new Matcher(
            fn (Matcher $matcher) => $matcher->setType('type'),
            fn (Matcher $matcher) => $matcher->setOptions(['examples' => 7]),
        ));
        $this->element->setGenerator(new Generator(
            fn (Generator $generator) => $generator->setType('Uuid'),
            fn (Generator $generator) => $generator->setOptions(['format' => 'simple']),
        ));

        $this->assertSame(<<<JSON
        {
            "value": {
                "name": "Child",
                "children": [],
                "attributes": {
                    "myAttr": "attr-value"
                }
            },
            "pact:matcher:type": "type",
            "examples": 7,
            "pact:generator:type": "Uuid",
            "format": "simple"
        }
        JSON, json_encode($this->element->getArray(), JSON_PRETTY_PRINT));
    }

    public function testGetBaseArray(): void
    {
        $this->assertSame(<<<JSON
        {
            "name": "Child",
            "children": [],
            "attributes": {
                "myAttr": "attr-value"
            }
        }
        JSON, json_encode($this->element->getArray(), JSON_PRETTY_PRINT));
    }
}
