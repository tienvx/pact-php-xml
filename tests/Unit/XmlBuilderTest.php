<?php

namespace Tienvx\PactPhpXml\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tienvx\PactPhpXml\XmlBuilder;

class XMLBuilderTest extends TestCase
{
    public function testGetArray(): void
    {
        $builder = new XmlBuilder('1.0', 'UTF-8');

        $builder
            ->root(
                $builder->name('Root'),
                $builder->add(
                    $builder->name('First Child Second Element'),
                    $builder->text(
                        $builder->contentLike('Example Test')
                    )
                ),
                $builder->add(
                    $builder->name('Second Parent'),
                    $builder->add(
                        $builder->name('Second child 1'),
                        $builder->attribute('myAttr', 'Attr Value')
                    ),
                    $builder->add(
                        $builder->name('Second child 2'),
                        $builder->text(
                            $builder->content('Test')
                        )
                    ),
                    $builder->add(
                        $builder->name('Third Parent'),
                        $builder->add(
                            $builder->eachLike(),
                            $builder->name('Child')
                        )
                    ),
                ),
                $builder->add(
                    $builder->name('First Child Third Element'),
                ),
            )
        ;

        $expectedJson = <<<JSON
        {
            "version": "1.0",
            "charset": "UTF-8",
            "root": {
                "name": "Root",
                "children": [
                    {
                        "name": "FirstChildSecondElement",
                        "children": [
                            {
                                "content": "Example Test",
                                "matcher": {
                                    "pact:matcher:type": "type"
                                }
                            }
                        ],
                        "attributes": {}
                    },
                    {
                        "name": "SecondParent",
                        "children": [
                            {
                                "name": "Secondchild1",
                                "children": [],
                                "attributes": {
                                    "myAttr": "Attr Value"
                                }
                            },
                            {
                                "name": "Secondchild2",
                                "children": [
                                    {
                                        "content": "Test"
                                    }
                                ],
                                "attributes": {}
                            },
                            {
                                "name": "ThirdParent",
                                "children": [
                                    {
                                        "value": {
                                            "name": "Child",
                                            "children": [],
                                            "attributes": {}
                                        },
                                        "pact:matcher:type": "type",
                                        "min": 1,
                                        "examples": 1
                                    }
                                ],
                                "attributes": {}
                            }
                        ],
                        "attributes": {}
                    },
                    {
                        "name": "FirstChildThirdElement",
                        "children": [],
                        "attributes": {}
                    }
                ],
                "attributes": {}
            }
        }
        JSON;

        $this->assertEquals($expectedJson, json_encode($builder->getArray(), JSON_PRETTY_PRINT));
    }
}
