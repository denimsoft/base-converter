# BaseConverter

BaseConverter is a library which allows converting numbers between bases of any length.

## Prequisites

- PHP >= 7.0
- bcmath

# Usage

```php
<?php

use Denimsoft\Stdlib\BaseConverter\BaseConverter;

require __DIR__ . '/vendor/autoload.php';

// create a base62 encoder
$baseConverter = new BaseConverter(62);

// encode a base10 value
$baseConverter->encode('18446744073709551615'); // LygHa16AHYF

// decode a base62 value
$baseConverter->decode('LygHa16AHYF'); // 18446744073709551615
```
