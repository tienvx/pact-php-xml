<?php

namespace Tienvx\PactPhpXml\Tests\Integration;

use PhpPact\Consumer\Matcher\Matcher;
use PHPUnit\Framework\TestCase;
use Tienvx\PactPhpXml\Model\Options;
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

    public function testBuildWithMatchersOnAttributes(): void
    {
        $this->xmlBuilder // @phpstan-ignore-line
            ->start('ns1:projects', ['id' => '1234', 'xmlns:ns1' => 'http://some.namespace/and/more/stuff'])
                ->eachLike('ns1:project', [
                    'id' => $this->matcher->integerV3(1),
                    'type' => 'activity',
                    'name' => $this->matcher->string('Project 1'),
                    'due' => $this->matcher->datetime("yyyy-MM-dd'T'HH:mm:ss.SZ", '2016-02-11T09:46:56.023Z')
                ], new Options(examples: 2))
                    ->start('ns1:tasks')
                        ->eachLike('ns1:task', [
                            'id' => $this->matcher->integerV3(1),
                            'name' => $this->matcher->string('Task 1'),
                            'done' => $this->matcher->boolean()
                        ], new Options(examples: 5))
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

    public function testBuildWithMatchersOnContent(): void
    {
        $this->xmlBuilder // @phpstan-ignore-line
            ->start('movies')
                ->eachLike('movie')
                    ->start('title')
                        ->contentLike('PHP: Behind the Parser')
                    ->end()
                    ->start('characters')
                        ->eachLike('character', [], new Options(examples: 2))
                            ->start('name')
                                ->contentLike('Ms. Coder')
                            ->end()
                            ->start('actor')
                                ->contentLike('Onlivia Actora')
                            ->end()
                        ->end()
                    ->end()
                    ->start('plot')
                        ->contentLike(
                            <<<EOF
                            So, this language. It's like, a programming language. Or is it a
                            scripting language? All is revealed in this thrilling horror spoof
                            of a documentary.
                            EOF
                        )
                    ->end()
                    ->start('great-lines')
                        ->eachLike('line')
                            ->contentLike('PHP solves all my web problems')
                        ->end()
                    ->end()
                    ->start('rating', ['type' => 'thumbs'])
                        ->contentLike(7)
                    ->end()
                    ->start('rating', ['type' => 'stars'])
                        ->contentLike(5)
                    ->end()
                ->end()
            ->end();

        $json = <<<JSON
        {
            "version": "1.0",
            "charset": "UTF-8",
            "root": {
                "name": "movies",
                "children": [
                    {
                        "pact:matcher:type": "type",
                        "value": {
                            "name": "movie",
                            "children": [
                                {
                                    "name": "title",
                                    "children": [
                                        {
                                            "content": "PHP: Behind the Parser",
                                            "matcher": {
                                                "pact:matcher:type": "type"
                                            }
                                        }
                                    ],
                                    "attributes": {}
                                },
                                {
                                    "name": "characters",
                                    "children": [
                                        {
                                            "pact:matcher:type": "type",
                                            "value": {
                                                "name": "character",
                                                "children": [
                                                    {
                                                        "name": "name",
                                                        "children": [
                                                            {
                                                                "content": "Ms. Coder",
                                                                "matcher": {
                                                                    "pact:matcher:type": "type"
                                                                }
                                                            }
                                                        ],
                                                        "attributes": {}
                                                    },
                                                    {
                                                        "name": "actor",
                                                        "children": [
                                                            {
                                                                "content": "Onlivia Actora",
                                                                "matcher": {
                                                                    "pact:matcher:type": "type"
                                                                }
                                                            }
                                                        ],
                                                        "attributes": {}
                                                    }
                                                ],
                                                "attributes": {}
                                            },
                                            "examples": 2
                                        }
                                    ],
                                    "attributes": {}
                                },
                                {
                                    "name": "plot",
                                    "children": [
                                        {
                                            "content": "So, this language. It's like, a programming language. Or is it a\\nscripting language? All is revealed in this thrilling horror spoof\\nof a documentary.",
                                            "matcher": {
                                                "pact:matcher:type": "type"
                                            }
                                        }
                                    ],
                                    "attributes": {}
                                },
                                {
                                    "name": "great-lines",
                                    "children": [
                                        {
                                            "pact:matcher:type": "type",
                                            "value": {
                                                "name": "line",
                                                "children": [
                                                    {
                                                        "content": "PHP solves all my web problems",
                                                        "matcher": {
                                                            "pact:matcher:type": "type"
                                                        }
                                                    }
                                                ],
                                                "attributes": {}
                                            },
                                            "examples": 1
                                        }
                                    ],
                                    "attributes": {}
                                },
                                {
                                    "name": "rating",
                                    "children": [
                                        {
                                            "content": 7,
                                            "matcher": {
                                                "pact:matcher:type": "type"
                                            }
                                        }
                                    ],
                                    "attributes": {
                                        "type": "thumbs"
                                    }
                                },
                                {
                                    "name": "rating",
                                    "children": [
                                        {
                                            "content": 5,
                                            "matcher": {
                                                "pact:matcher:type": "type"
                                            }
                                        }
                                    ],
                                    "attributes": {
                                        "type": "stars"
                                    }
                                }
                            ],
                            "attributes": {}
                        },
                        "examples": 1
                    }
                ],
                "attributes": {}
            }
        }
        JSON;

        $this->assertSame($json, json_encode($this->xmlBuilder->getArray(), JSON_PRETTY_PRINT));
    }
}
