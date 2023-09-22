<?php

namespace Tienvx\PactPhpXml\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tienvx\PactPhpXml\Model\Matcher\Generator;
use Tienvx\PactPhpXml\Model\Matcher\Matcher;
use Tienvx\PactPhpXml\XmlText;

class XmlTextTest extends TestCase
{
    private XmlText $text;

    public function setUp(): void
    {
        $this->text = new XmlText();
        $this->text->setContent('testing');
    }

    public function testGetMatcherArray(): void
    {
        $this->text->setMatcher(new Matcher(
            fn (Matcher $matcher) => $matcher->setType('include'),
            fn (Matcher $matcher) => $matcher->setOptions(['value' => "te"]),
        ));

        $this->assertSame(<<<JSON
        {
            "content": "testing",
            "matcher": {
                "pact:matcher:type": "include",
                "value": "te"
            }
        }
        JSON, json_encode($this->text->getArray(), JSON_PRETTY_PRINT));
    }

    public function testGetGeneratorArray(): void
    {
        $this->text->setContent(7);
        $this->text->setMatcher(new Matcher(
            fn (Matcher $matcher) => $matcher->setType('integer'),
        ));
        $this->text->setGenerator(new Generator(
            fn (Generator $generator) => $generator->setType('RandomInt'),
            fn (Generator $generator) => $generator->setOptions(['min' => 2, 'max' => 8]),
        ));

        $this->assertSame(<<<JSON
        {
            "content": 7,
            "matcher": {
                "pact:matcher:type": "integer",
                "min": 2,
                "max": 8
            },
            "pact:generator:type": "RandomInt"
        }
        JSON, json_encode($this->text->getArray(), JSON_PRETTY_PRINT));
    }

    public function testGetBaseArray(): void
    {
        $this->assertSame(<<<JSON
        {
            "content": "testing"
        }
        JSON, json_encode($this->text->getArray(), JSON_PRETTY_PRINT));
    }
}
