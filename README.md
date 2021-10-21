# bancore-api-client-php

[![Latest Stable Version](https://img.shields.io/github/v/release/brokeyourbike/bancore-api-client-php)](https://github.com/brokeyourbike/bancore-api-client-php/releases)
[![Total Downloads](https://poser.pugx.org/brokeyourbike/bancore-api-client/downloads)](https://packagist.org/packages/brokeyourbike/bancore-api-client)
[![License: MPL-2.0](https://img.shields.io/badge/license-MPL--2.0-purple.svg)](https://github.com/brokeyourbike/bancore-api-client-php/blob/main/LICENSE)

[![Maintainability](https://api.codeclimate.com/v1/badges/8bcbb3d869b4e6fe42a9/maintainability)](https://codeclimate.com/github/brokeyourbike/bancore-api-client-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/8bcbb3d869b4e6fe42a9/test_coverage)](https://codeclimate.com/github/brokeyourbike/bancore-api-client-php/test_coverage)
[![tests](https://github.com/brokeyourbike/bancore-api-client-php/actions/workflows/tests.yml/badge.svg)](https://github.com/brokeyourbike/bancore-api-client-php/actions/workflows/tests.yml)

Bancore API Client for PHP

## Installation

```bash
composer require brokeyourbike/bancore-api-client
```

## Usage

```php
use BrokeYourBike\Bancore\Client;

$apiClient = new Client($config, $httpClient);
$apiClient->fetchBalanceRaw();
```

## License
[Mozilla Public License v2.0](https://github.com/brokeyourbike/bancore-api-client-php/blob/main/LICENSE)

