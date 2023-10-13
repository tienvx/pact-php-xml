# Pact PHP XML [![Build Status][actions_badge]][actions_link] [![Coverage Status][coveralls_badge]][coveralls_link] [![Version][version-image]][version-url] [![PHP Version][php-version-image]][php-version-url]

This library contains classes support xml for [Pact PHP][pact-php].

## Installation

```shell
composer require tienvx/pact-php-xml
```

## Usage

```php
<?php

require_once 'vendor/autoload.php';

use AaronDDM\XMLBuilder\Exception\XMLBuilderException;
use PhpPact\Consumer\Matcher;
use Tienvx\PactPhpXml\Model\Options;
use Tienvx\PactPhpXml\XmlBuilder;

$builder = new XmlBuilder();
$matcher = new Matcher();

try {
    $builder
        ->root(
            $builder->name('ns1:projects'),
            $builder->attribute('id', '1234'),
            $builder->attribute('xmlns:ns1', 'http://some.namespace/and/more/stuff'),
            $builder->add(
                $builder->eachLike(examples: 2),
                $builder->name('ns1:project'),
                $builder->attribute('id', $matcher->integerV3(1)),
                $builder->attribute('type', 'activity'),
                $builder->attribute('name', $matcher->string('Project 1')),
                $builder->attribute('due', $matcher->datetime("yyyy-MM-dd'T'HH:mm:ss.SZ", '2016-02-11T09:46:56.023Z')),
                $builder->add(
                    $builder->name('ns1:tasks'),
                    $builder->add(
                        $builder->eachLike(examples: 5),
                        $builder->name('ns1:task'),
                        $builder->attribute('id', $matcher->integerV3(1)),
                        $builder->attribute('name', $matcher->string('Task 1')),
                        $builder->attribute('done', $matcher->boolean()),
                    ),
                ),
            ),
        );

    var_dump($builder->getArray());
} catch (XMLBuilderException $e) {
    var_dump('An exception occurred: ' . $e->getMessage());
}
```

## License

[MIT](https://github.com/tienvx/pact-php-xml/blob/main/LICENSE)

[actions_badge]: https://github.com/tienvx/pact-php-xml/workflows/main/badge.svg
[actions_link]: https://github.com/tienvx/pact-php-xml/actions

[coveralls_badge]: https://coveralls.io/repos/tienvx/pact-php-xml/badge.svg?branch=main&service=github
[coveralls_link]: https://coveralls.io/github/tienvx/pact-php-xml?branch=main

[version-url]: https://packagist.org/packages/tienvx/pact-php-xml
[version-image]: http://img.shields.io/packagist/v/tienvx/pact-php-xml.svg?style=flat

[php-version-url]: https://packagist.org/packages/tienvx/pact-php-xml
[php-version-image]: http://img.shields.io/badge/php-8.0.0+-ff69b4.svg

[pact-php]: https://github.com/pact-foundation/pact-php
