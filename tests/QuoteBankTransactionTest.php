<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Tests;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\Bancore\Interfaces\TransactionInterface;
use BrokeYourBike\Bancore\Interfaces\ConfigInterface;
use BrokeYourBike\Bancore\Client;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 */
class QuoteBankTransactionTest extends TestCase
{
    private string $token = 'secure-token';
    private string $sessionId = 'session-id';

    /**
     * @test
     * @dataProvider isLiveProvider
     */
    public function it_can_prepare_request(bool $isLive): void
    {
        /** @var TransactionInterface $transaction */
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('isLive')->willReturn($isLive);
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                    "responseCode": "1000",
                    "responseDescription": "Successful operation",
                }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/transactions/quotations/bank-account',
            [
                \GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$this->token}",
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'accountNumber' => $transaction->getRecipient()->getBankAccount(),
                    'beneficiaryCountry' => $transaction->getRecipient()->getCountryCode(),
                    'beneficiaryCurrency' => $transaction->getReceiveCurrencyCode(),
                    'beneficiaryMobileNumber' => $transaction->getRecipient()->getPhoneNumber(),
                    'senderAmount' => $transaction->getReceiveAmount(),
                    'senderCurrency' => $transaction->getSendCurrencyCode(),
                    'senderMobileNumber' => $transaction->getSender()->getPhoneNumber(),
                    'sessionId' => $this->sessionId,
                ],
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

        $requestResult = $api->quoteBankTransaction($this->sessionId, $transaction);

        $this->assertInstanceOf(ResponseInterface::class, $requestResult);
    }

    /**
     * @test
     * @dataProvider isLiveProvider
     */
    public function it_will_pass_source_model_as_option(bool $isLive): void
    {
        /** @var SourceTransactionFixture $transaction */
        $transaction = $this->getMockBuilder(SourceTransactionFixture::class)->getMock();

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('isLive')->willReturn($isLive);
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/transactions/quotations/bank-account',
            [
                \GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$this->token}",
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'accountNumber' => $transaction->getRecipient()->getBankAccount(),
                    'beneficiaryCountry' => $transaction->getRecipient()->getCountryCode(),
                    'beneficiaryCurrency' => $transaction->getReceiveCurrencyCode(),
                    'beneficiaryMobileNumber' => $transaction->getRecipient()->getPhoneNumber(),
                    'senderAmount' => $transaction->getReceiveAmount(),
                    'senderCurrency' => $transaction->getSendCurrencyCode(),
                    'senderMobileNumber' => $transaction->getSender()->getPhoneNumber(),
                    'sessionId' => $this->sessionId,
                ],
                \BrokeYourBike\HasSourceModel\Enums\RequestOptions::SOURCE_MODEL => $transaction,
            ],
        ])->once();

        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $mockedCache->method('has')->willReturn(true);
        $mockedCache->method('get')->willReturn($this->token);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        $requestResult = $api->quoteBankTransaction($this->sessionId, $transaction);

        $this->assertInstanceOf(ResponseInterface::class, $requestResult);
    }
}
