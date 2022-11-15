# Alibaba SDK
[![build](https://github.com/kyto-gmbh/alibaba-sdk-php/actions/workflows/build.yml/badge.svg)](https://github.com/kyto-gmbh/alibaba-sdk-php/actions/workflows/build.yml)

Alibaba SDK for PHP. This package provides structured interface to communicate with [Alibaba Open Platform](https://developer.alibaba.com/en/doc.htm?spm=a219a.7629140.0.0.188675fe5JPvEa#?docType=1&docId=118496).

## Installation
Install the latest version with [Composer](https://getcomposer.org/):
```bash
composer require kyto/alibaba-sdk
```

## Usage example
All the interactions with Alibaba are done via `Kyto\Alibaba\Facade`.

```php
require __DIR__ . '/vendor/autoload.php';

use Kyto\Alibaba\Facade;

$alibaba = Facade::create('api-key', 'api-secret');
$alibaba->category->get('0'); // @return Kyto\Alibaba\Model\Category
```

## Endpoints
```text
facade
├─ getAuthorizationUrl - Get user authorization url
└─ category/           - Category endpoint
   ├─ get                  - Get product listing category
   └─ getAttributes        - Get system-defined attributes based on category ID
```

## Credits
[Kyto GmbH](https://kyto.com/)  
Licensed under the Apache-2.0 License. See [LICENSE](LICENSE) for more information.  
