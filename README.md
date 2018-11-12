# Data Dot

[![Build Status](https://travis-ci.org/extphp/data-dot.svg?branch=master)](https://travis-ci.org/extphp/data-dot)
[![Latest Stable Version](https://poser.pugx.org/extphp/data-dot/v/stable)](https://packagist.org/packages/extphp/data-dot)
[![License](https://poser.pugx.org/extphp/data-dot/license)](https://packagist.org/packages/extphp/data-dot)
[![Total Downloads](https://poser.pugx.org/extphp/data-dot/downloads)](https://packagist.org/packages/extphp/data-dot)


A simple dot notation accessor, able to handle a mix of arrays and objects.


## Usage

```php
<?php

use ExtPHP\DataDot\Dot;

$data = [
    'eyes'  => 'blue',
    'age'   => '27',
    'parents' => [
        'mother'    => 'Jane',
        'father'    => 'Jack'
    ]
];

$dot = new Dot($data);

$dot->get('parents.father', 'John');    // returns 'Jack'
$dot->get('sister', 'Kate');            // returns 'Kate'
```

## Testing
```bash
php vendor/bin/phpunit
```
