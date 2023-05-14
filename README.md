# bancore-api-client

[![Latest Stable Version](https://img.shields.io/github/v/release/brokeyourbike/bancore-api-client-php)](https://github.com/brokeyourbike/bancore-api-client-php/releases)
[![Total Downloads](https://poser.pugx.org/brokeyourbike/bancore-api-client/downloads)](https://packagist.org/packages/brokeyourbike/bancore-api-client)
[![Maintainability](https://api.codeclimate.com/v1/badges/8bcbb3d869b4e6fe42a9/maintainability)](https://codeclimate.com/github/brokeyourbike/bancore-api-client-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/8bcbb3d869b4e6fe42a9/test_coverage)](https://codeclimate.com/github/brokeyourbike/bancore-api-client-php/test_coverage)

Bancore API Client for PHP

## Installation

```bash
composer require brokeyourbike/bancore-api-client
```

## Usage

```php
use BrokeYourBike\Bancore\Client;

$apiClient = new Client($config, $httpClient, $psrCache);
$apiClient->fetchAuthTokenRaw();
```

## Authors
- [Ivan Stasiuk](https://github.com/brokeyourbike) | [Twitter](https://twitter.com/brokeyourbike) | [LinkedIn](https://www.linkedin.com/in/brokeyourbike) | [stasi.uk](https://stasi.uk)

## License
[Mozilla Public License v2.0](https://github.com/brokeyourbike/bancore-api-client-php/blob/main/LICENSE)

