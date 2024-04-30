# Alibaba SDK
[![build](https://github.com/kyto-gmbh/alibaba-sdk-php/actions/workflows/build.yml/badge.svg)](https://github.com/kyto-gmbh/alibaba-sdk-php/actions/workflows/build.yml)

Alibaba SDK for PHP. This package provides a structured interface to communicate with [Alibaba Open Platform](https://openapi.alibaba.com/doc/doc.htm?spm=a2o9m.11223882.0.0.1566722cTOuz7W#/?docId=19).

> Note, package is in development therefore public interface could be changed in future releases.

## Requirements
Currently, the minimum required PHP version is **PHP 8.1**.  
See the [composer.json](composer.json) for other requirements.  

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

$alibaba = Facade::create('app-key', 'app-secret');
$alibaba->category->get('0'); // @return Kyto\Alibaba\Model\Category
```

## Endpoints
Currently implemented endpoints:

```text
facade
├─ getAuthorizationUrl - Get user authorization url
├─ token/              - Token endpoint
│  └─ new                  - Obtain new session token
├─ category/           - Category endpoint
│  ├─ get                  - Get product listing category
│  ├─ getAttributes        - Get system-defined attributes based on category ID
│  └─ getLevelAttribute    - Get next-level attribute based on category, attribute and value ID (e.g. car_model values)
└─ product/            - Product endpoint
   └─ getGroup             - Get product group
```

## Credits
[Kyto GmbH](https://kyto.com/)  
Licensed under the Apache-2.0 License. See [LICENSE](LICENSE) for more information.  
