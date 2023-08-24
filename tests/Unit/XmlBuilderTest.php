<?php

namespace Tienvx\PactPhpXml\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tienvx\PactPhpXml\XmlBuilder;

class XMLBuilderTest extends TestCase
{
    public function testGetArray()
    {
        $xmlBuilder = new XmlBuilder('1.0', 'UTF-8');

        $xmlBuilder
            ->start('Root')
                ->add('First Child Second Element', 'False')
                ->start('Second Parent')
                    ->add('Second child 1', null, ['myAttr' => 'Attr Value'])
                    ->add('Second child 2', 'False')
                    ->start('Third Parent')
                        ->add('Child')
                    ->end()
                ->end()
                ->add('First Child Third Element')
            ->end()
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
                        "children": "False",
                        "attributes": {}
                    },
                    {
                        "name": "SecondParent",
                        "children": [
                            {
                                "name": "Secondchild1",
                                "children": null,
                                "attributes": {
                                    "myAttr": "Attr Value"
                                }
                            },
                            {
                                "name": "Secondchild2",
                                "children": "False",
                                "attributes": {}
                            },
                            {
                                "name": "ThirdParent",
                                "children": [
                                    {
                                        "name": "Child",
                                        "children": null,
                                        "attributes": {}
                                    }
                                ],
                                "attributes": {}
                            }
                        ],
                        "attributes": {}
                    },
                    {
                        "name": "FirstChildThirdElement",
                        "children": null,
                        "attributes": {}
                    }
                ],
                "attributes": {}
            }
        }
        JSON;

        $this->assertEquals($expectedJson, json_encode($xmlBuilder->getArray(), JSON_PRETTY_PRINT));
    }
}