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
use Tienvx\PactPhpXml\XmlBuilder;

$xmlBuilder = new XmlBuilder();
$matcher = new Matcher();

try {
    $xmlBuilder
        ->start('ns1:projects', ['id' => '1234', 'xmlns:ns1' => 'http://some.namespace/and/more/stuff'])
            ->eachLike('ns1:project', [
                'id' => $matcher->integerV3(1),
                'type' => 'activity',
                'name' => $matcher->string('Project 1'),
                'due' => $matcher->datetime("yyyy-MM-dd'T'HH:mm:ss.SZ", '2016-02-11T09:46:56.023Z')
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

    var_dump($xmlBuilder->getArray());
} catch (XMLBuilderException $e) {
    var_dump('An exception occurred: ' . $e->getMessage());
}
```

TBD

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
