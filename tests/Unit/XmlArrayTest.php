<?php

namespace Tienvx\PactPhpXml\Tests\Unit;

use ReflectionProperty;
use Tienvx\PactPhpXml\Model\Matcher;
use Tienvx\PactPhpXml\Model\Options;
use Tienvx\PactPhpXml\XmlArray;
use PHPUnit\Framework\TestCase;
use Tienvx\PactPhpXml\XmlContent;
use Tienvx\PactPhpXml\XmlElementData;

class XmlArrayTest extends TestCase
{
    public function testEachLike(): void
    {
        $options = new Options(examples: 5);
        /** @var XmlArray */
        $parent = XmlArray::initiate(XmlElementData::class);
        $child = $parent->eachLike('Child', ['myAttr' => 'attr-value'], $options);
        $stack = $this->getProperty($parent, 'stack');
        $element = end($stack);

        $this->assertSame($parent, $this->getProperty($child, 'parent'));
        $this->assertInstanceOf(XmlElementData::class, $element);
        $this->assertInstanceOf(Matcher::class, $this->getProperty($element, 'matcher'));
        $this->assertSame($options, $this->getProperty($element, 'options'));
    }

    public function testContentLike(): void
    {
        $content = 'Testing';
        /** @var XmlArray */
        $root = XmlArray::initiate(XmlElementData::class);
        $root->contentLike($content);
        $stack = $this->getProperty($root, 'stack');
        $element = end($stack);

        $this->assertInstanceOf(XmlContent::class, $element);
        $this->assertInstanceOf(Matcher::class, $this->getProperty($element, 'matcher'));
        $this->assertSame($content, $this->getProperty($element, 'content'));
    }

    private function getProperty(object $object, string $property): mixed
    {
        $reflection = new ReflectionProperty($object, $property);
        $reflection->setAccessible(true);

        return $reflection->getValue($object);
    }
}
