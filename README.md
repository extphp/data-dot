# Data Dot

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
