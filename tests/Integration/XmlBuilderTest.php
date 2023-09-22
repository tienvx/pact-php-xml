<?php

namespace Tienvx\PactPhpXml\Tests\Integration;

use PhpPact\Consumer\Matcher\Matcher;
use PHPUnit\Framework\TestCase;
use Tienvx\PactPhpXml\XmlBuilder;

class XmlBuilderTest extends TestCase
{
    private XmlBuilder $builder;
    private Matcher $matcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->builder = new XmlBuilder('1.0', 'UTF-8');
        $this->matcher = new Matcher();
    }

    public function testBuildWithMatchersOnAttributes(): void
    {
        $this->builder
            ->root(
                $this->builder->name('ns1:projects'),
                $this->builder->attribute('id', '1234'),
                $this->builder->attribute('xmlns:ns1', 'http://some.namespace/and/more/stuff'),
                $this->builder->add(
                    $this->builder->eachLike(examples: 2),
                    $this->builder->name('ns1:project'),
                    $this->builder->attribute('id', $this->matcher->integerV3(1)),
                    $this->builder->attribute('type', 'activity'),
                    $this->builder->attribute('name', $this->matcher->string('Project 1')),
                    $this->builder->attribute('due', $this->matcher->datetime("yyyy-MM-dd'T'HH:mm:ss.SZ", '2016-02-11T09:46:56.023Z')),
                    $this->builder->add(
                        $this->builder->name('ns1:tasks'),
                        $this->builder->add(
                            $this->builder->eachLike(examples: 5),
                            $this->builder->name('ns1:task'),
                            $this->builder->attribute('id', $this->matcher->integerV3(1)),
                            $this->builder->attribute('name', $this->matcher->string('Task 1')),
                            $this->builder->attribute('done', $this->matcher->boolean()),
                        ),
                    ),
                ),
            );

        $json = <<<JSON
        {
            "version": "1.0",
            "charset": "UTF-8",
            "root": {
                "name": "ns1:projects",
                "children": [
                    {
                        "value": {
                            "name": "ns1:project",
                            "children": [
                                {
                                    "name": "ns1:tasks",
                                    "children": [
                                        {
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
                                            "pact:matcher:type": "type",
                                            "min": 1,
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
                        "pact:matcher:type": "type",
                        "min": 1,
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

        $this->assertSame($json, json_encode($this->builder->getArray(), JSON_PRETTY_PRINT));
    }

    public function testBuildWithMatchersOnContent(): void
    {
        $this->builder
            ->root(
                $this->builder->name('movies'),
                $this->builder->add(
                    $this->builder->eachLike(),
                    $this->builder->name('movie'),
                    $this->builder->add(
                        $this->builder->name('title'),
                        $this->builder->text(
                            $this->builder->contentLike('PHP: Behind the Parser'),
                        ),
                    ),
                    $this->builder->add(
                        $this->builder->name('characters'),
                        $this->builder->add(
                            $this->builder->eachLike(examples: 2),
                            $this->builder->name('character'),
                            $this->builder->add(
                                $this->builder->name('name'),
                                $this->builder->text(
                                    $this->builder->contentLike('Ms. Coder'),
                                ),
                            ),
                            $this->builder->add(
                                $this->builder->name('actor'),
                                $this->builder->text(
                                    $this->builder->contentLike('Onlivia Actora'),
                                ),
                            ),
                        ),
                    ),
                    $this->builder->add(
                        $this->builder->name('plot'),
                        $this->builder->text(
                            $this->builder->contentLike(
                                <<<EOF
                                So, this language. It's like, a programming language. Or is it a
                                scripting language? All is revealed in this thrilling horror spoof
                                of a documentary.
                                EOF
                            ),
                        ),
                    ),
                    $this->builder->add(
                        $this->builder->name('great-lines'),
                        $this->builder->add(
                            $this->builder->eachLike(),
                            $this->builder->name('line'),
                            $this->builder->text(
                                $this->builder->contentLike('PHP solves all my web problems'),
                            ),
                        ),
                    ),
                    $this->builder->add(
                        $this->builder->name('rating'),
                        $this->builder->attribute('type', 'thumbs'),
                        $this->builder->text(
                            $this->builder->contentLike(7),
                        ),
                    ),
                    $this->builder->add(
                        $this->builder->name('rating'),
                        $this->builder->attribute('type', 'stars'),
                        $this->builder->text(
                            $this->builder->contentLike(5),
                        ),
                    ),
                ),
            );

        $json = <<<JSON
        {
            "version": "1.0",
            "charset": "UTF-8",
            "root": {
                "name": "movies",
                "children": [
                    {
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
                                            "pact:matcher:type": "type",
                                            "min": 1,
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
                                            "pact:matcher:type": "type",
                                            "min": 1,
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
                        "pact:matcher:type": "type",
                        "min": 1,
                        "examples": 1
                    }
                ],
                "attributes": {}
            }
        }
        JSON;

        $this->assertSame($json, json_encode($this->builder->getArray(), JSON_PRETTY_PRINT));
    }
}
