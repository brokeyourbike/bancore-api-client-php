<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Tests;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use Carbon\Carbon;
use BrokeYourBike\Bancore\Interfaces\ConfigInterface;
use BrokeYourBike\Bancore\Client;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 */
class FetchExhangeRatesForRawTest extends TestCase
{
    private string $token = 'secure-token';
    private string $currencyCode = 'USD';

    /** @test */
    public function it_can_prepare_request(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "requestDate": "' . Carbon::now()->format('Y-m-d H:i:s') . '",
                "requestCurrency": "' . $this->currencyCode . '",
                "quotationStatus": "9000:Success",
                "quotes": [
                    {
                        "receivingServiceProvider": "NG",
                        "receivingCurrency": "NGN",
                        "fxRate": "400.00"
                    }
            ]}');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'GET',
            "https://api.example/transactions/exchange-rates/{$this->currencyCode}",
            [
                \GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$this->token}",
                ],
                \GuzzleHttp\RequestOptions::JSON => [],
            ],
        ])->once()->andReturn($mockedResponse);

        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $mockedCache->method('has')->willReturn(true);
        $mockedCache->method('get')->willReturn($this->token);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        $requestResult = $api->fetchExhangeRatesForRaw($this->currencyCode);

        $this->assertInstanceOf(ResponseInterface::class, $requestResult);
    }
}
