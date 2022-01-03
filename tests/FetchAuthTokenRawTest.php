<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Tests;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\Bancore\Models\FetchAuthTokenResponse;
use BrokeYourBike\Bancore\Interfaces\ConfigInterface;
use BrokeYourBike\Bancore\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class FetchAuthTokenRawTest extends TestCase
{
    private string $username = 'admin';
    private string $password = 'super-secure-password';

    /** @test */
    public function it_can_prepare_request(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getUsername')->willReturn($this->username);
        $mockedConfig->method('getPassword')->willReturn($this->password);

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "uuid": "ed0d0d9c-8591-4b24-b738-6e511f50da8a",
                "username": "user@example.com",
                "firstName": "JOHN",
                "lastName": "DOE",
                "email": "user@example.com",
                "token": "123456789",
                "expiresIn": 86400,
                "lastLoggedIn": "2022-01-03 04:43:32",
                "createdAt": "2021-04-14 11:00:00"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/auth',
            [
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'username' => $this->username,
                    'password' => $this->password,
                    'rememberMe' => true,
                ],
            ],
        ])->once()->andReturn($mockedResponse);

        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);
        $response = $api->fetchAuthTokenRaw();

        $this->assertInstanceOf(FetchAuthTokenResponse::class, $response);
        $this->assertSame(86400, $response->expiresIn);
        $this->assertSame('123456789', $response->token);
    }
}
