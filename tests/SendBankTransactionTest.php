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
use BrokeYourBike\Bancore\Interfaces\SenderInterface;
use BrokeYourBike\Bancore\Interfaces\RecipientInterface;
use BrokeYourBike\Bancore\Interfaces\QuotaInterface;
use BrokeYourBike\Bancore\Interfaces\IdentifierSourceInterface;
use BrokeYourBike\Bancore\Interfaces\ConfigInterface;
use BrokeYourBike\Bancore\Exceptions\PrepareRequestException;
use BrokeYourBike\Bancore\Client;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 */
class SendBankTransactionTest extends TestCase
{
    private string $token = 'secure-token';
    private SenderInterface $sender;
    private RecipientInterface $recipient;
    private QuotaInterface $quota;
    private IdentifierSourceInterface $identifierSource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sender = $this->getMockBuilder(SenderInterface::class)->getMock();
        $this->recipient = $this->getMockBuilder(RecipientInterface::class)->getMock();
        $this->quota = $this->getMockBuilder(QuotaInterface::class)->getMock();
        $this->identifierSource = $this->getMockBuilder(IdentifierSourceInterface::class)->getMock();
    }

    /** @test */
    public function it_will_throw_if_no_sender_in_transaction()
    {
        /** @var TransactionInterface $transaction */
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();

        $this->assertNull($transaction->getSender());

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedClient = $this->getMockBuilder(\GuzzleHttp\ClientInterface::class)->getMock();
        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();

        $this->expectExceptionMessage(SenderInterface::class . ' is required');
        $this->expectException(PrepareRequestException::class);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        $api->sendBankTransaction($transaction);
    }

    /** @test */
    public function it_will_throw_if_no_recipient_in_transaction()
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getSender')->willReturn($this->sender);

        /** @var TransactionInterface $transaction */
        $this->assertNull($transaction->getRecipient());

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedClient = $this->getMockBuilder(\GuzzleHttp\ClientInterface::class)->getMock();
        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();

        $this->expectExceptionMessage(RecipientInterface::class . ' is required');
        $this->expectException(PrepareRequestException::class);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        $api->sendBankTransaction($transaction);
    }

    /** @test */
    public function it_will_throw_if_no_quota_in_transaction()
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getSender')->willReturn($this->sender);
        $transaction->method('getRecipient')->willReturn($this->recipient);

        /** @var TransactionInterface $transaction */
        $this->assertNull($transaction->getQuota());

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedClient = $this->getMockBuilder(\GuzzleHttp\ClientInterface::class)->getMock();
        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();

        $this->expectExceptionMessage(QuotaInterface::class . ' is required');
        $this->expectException(PrepareRequestException::class);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        $api->sendBankTransaction($transaction);
    }

    /** @test */
    public function it_will_throw_if_no_identifier_source_in_transaction()
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getSender')->willReturn($this->sender);
        $transaction->method('getRecipient')->willReturn($this->recipient);
        $transaction->method('getQuota')->willReturn($this->quota);

        /** @var TransactionInterface $transaction */
        $this->assertNull($transaction->getIdentifierSource());

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedClient = $this->getMockBuilder(\GuzzleHttp\ClientInterface::class)->getMock();
        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();

        $this->expectExceptionMessage(IdentifierSourceInterface::class . ' is required');
        $this->expectException(PrepareRequestException::class);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        $api->sendBankTransaction($transaction);
    }

    /** @test */
    public function it_can_prepare_request(): void
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getSender')->willReturn($this->sender);
        $transaction->method('getRecipient')->willReturn($this->recipient);
        $transaction->method('getQuota')->willReturn($this->quota);
        $transaction->method('getIdentifierSource')->willReturn($this->identifierSource);

        /** @var TransactionInterface $transaction */
        $this->assertInstanceOf(TransactionInterface::class, $transaction);

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
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
            'https://api.example/transactions/remittances/bank-account',
            [
                \GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$this->token}",
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'sessionId' => $transaction->getReference(),
                    'identifier' => $transaction->getIdentifierSource()?->getCode(),
                    'quoteId' => $transaction->getQuota()?->getReference(),
                    'accountNumber' => $transaction->getRecipient()?->getBankAccount(),
                    'bankCode' => $transaction->getRecipient()?->getBankCode(),
                    'beneficiaryAmount' => $transaction->getReceiveAmount(),
                    'beneficiaryCountry' => $transaction->getRecipient()?->getCountryCode(),
                    'beneficiaryCurrency' => $transaction->getReceiveCurrencyCode(),
                    'beneficiaryDetails' => [
                        'fullName' => $transaction->getRecipient()?->getName(),
                    ],
                    'beneficiaryMobileNumber' => $transaction->getRecipient()?->getPhoneNumber(),
                    'senderCurrency' => $transaction->getSendCurrencyCode(),
                    'senderDetails' => [
                        'fullName' => $transaction->getSender()?->getName(),
                        'address' => [
                            'country' => $transaction->getSender()?->getCountryCode(),
                        ],
                    ],
                    'senderMobileNumber' => $transaction->getSender()?->getPhoneNumber(),
                    'description' => $transaction->getReference(),
                ],
                // RequestOptions::SOURCE_MODEL => $transaction,
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

        $requestResult = $api->sendBankTransaction($transaction);

        $this->assertInstanceOf(ResponseInterface::class, $requestResult);
    }

    /** @test */
    public function it_will_pass_source_model_as_option(): void
    {
        $transaction = $this->getMockBuilder(SourceTransactionFixture::class)->getMock();
        $transaction->method('getSender')->willReturn($this->sender);
        $transaction->method('getRecipient')->willReturn($this->recipient);
        $transaction->method('getQuota')->willReturn($this->quota);
        $transaction->method('getIdentifierSource')->willReturn($this->identifierSource);

        /** @var SourceTransactionFixture $transaction */
        $this->assertInstanceOf(SourceTransactionFixture::class, $transaction);

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/transactions/remittances/bank-account',
            [
                \GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$this->token}",
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'sessionId' => $transaction->getReference(),
                    'identifier' => $transaction->getIdentifierSource()?->getCode(),
                    'quoteId' => $transaction->getQuota()?->getReference(),
                    'accountNumber' => $transaction->getRecipient()?->getBankAccount(),
                    'bankCode' => $transaction->getRecipient()?->getBankCode(),
                    'beneficiaryAmount' => $transaction->getReceiveAmount(),
                    'beneficiaryCountry' => $transaction->getRecipient()?->getCountryCode(),
                    'beneficiaryCurrency' => $transaction->getReceiveCurrencyCode(),
                    'beneficiaryDetails' => [
                        'fullName' => $transaction->getRecipient()?->getName(),
                    ],
                    'beneficiaryMobileNumber' => $transaction->getRecipient()?->getPhoneNumber(),
                    'senderCurrency' => $transaction->getSendCurrencyCode(),
                    'senderDetails' => [
                        'fullName' => $transaction->getSender()?->getName(),
                        'address' => [
                            'country' => $transaction->getSender()?->getCountryCode(),
                        ],
                    ],
                    'senderMobileNumber' => $transaction->getSender()?->getPhoneNumber(),
                    'description' => $transaction->getReference(),
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

        $requestResult = $api->sendBankTransaction($transaction);

        $this->assertInstanceOf(ResponseInterface::class, $requestResult);
    }
}
