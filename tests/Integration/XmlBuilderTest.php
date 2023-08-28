<?php

namespace Tienvx\PactPhpXml\Tests\Integration;

use PhpPact\Consumer\Matcher\Matcher;
use PHPUnit\Framework\TestCase;
use Tienvx\PactPhpXml\XmlBuilder;

class XmlBuilderTest extends TestCase
{
    private XmlBuilder $xmlBuilder;
    private Matcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->xmlBuilder = new XmlBuilder('1.0', 'UTF-8');
        $this->matcher = new Matcher();
    }

    public function testBuild(): void
    {
        $this->xmlBuilder // @phpstan-ignore-line
            ->start('ns1:projects', ['id' => '1234', 'xmlns:ns1' => 'http://some.namespace/and/more/stuff'])
                ->eachLike('ns1:project', [
                    'id' => $this->matcher->integerV3(1),
                    'type' => 'activity',
                    'name' => $this->matcher->string('Project 1'),
                    'due' => $this->matcher->datetime("yyyy-MM-dd'T'HH:mm:ss.SZ", '2016-02-11T09:46:56.023Z')
                ], ['examples' => 2])
                    ->start('ns1:tasks')
                        ->eachLike('ns1:task', [
                            'id' => $this->matcher->integerV3(1),
                            'name' => $this->matcher->string('Task 1'),
                            'done' => $this->matcher->boolean()
                        ], ['examples' => 5])
                        ->end()
                    ->end()
                ->end()
            ->end();

        $json = <<<JSON
        {
            "version": "1.0",
            "charset": "UTF-8",
            "root": {
                "name": "ns1:projects",
                "children": [
                    {
                        "pact:matcher:type": "type",
                        "value": {
                            "name": "ns1:project",
                            "children": [
                                {
                                    "name": "ns1:tasks",
                                    "children": [
                                        {
                                            "pact:matcher:type": "type",
                                            "value": {
                                                "name": "ns1:task",
                                                "children": [],
                                                "attributes": {
                                                    "id": {
                                                        "value": 1,
                                                        "pact:matcher:type": "integer"
                                                    },
                                                    "name": {
                                                        "value": "Task 1",
                                                        "pact:matcher:type": "type"
                                                    },
                                                    "done": {
                                                        "value": true,
                                                        "pact:matcher:type": "type"
                                                    }
                                                }
                                            },
                                            "examples": 5
                                        }
                                    ],
                                    "attributes": {}
                                }
                            ],
                            "attributes": {
                                "id": {
                                    "value": 1,
                                    "pact:matcher:type": "integer"
                                },
                                "type": "activity",
                                "name": {
                                    "value": "Project 1",
                                    "pact:matcher:type": "type"
                                },
                                "due": {
                                    "value": "2016-02-11T09:46:56.023Z",
                                    "pact:matcher:type": "datetime",
                                    "format": "yyyy-MM-dd'T'HH:mm:ss.SZ"
                                }
                            }
                        },
                        "examples": 2
                    }
                ],
                "attributes": {
                    "id": "1234",
                    "xmlns:ns1": "http:\/\/some.namespace\/and\/more\/stuff"
                }
            }
        }
        JSON;

        $this->assertSame($json, json_encode($this->xmlBuilder->getArray(), JSON_PRETTY_PRINT));
    }
}
