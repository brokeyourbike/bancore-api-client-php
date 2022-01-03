<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Tests;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\Bancore\Models\ValidateBankTransactionResponse;
use BrokeYourBike\Bancore\Interfaces\TransactionInterface;
use BrokeYourBike\Bancore\Interfaces\SenderInterface;
use BrokeYourBike\Bancore\Interfaces\RecipientInterface;
use BrokeYourBike\Bancore\Interfaces\IdentifierSourceInterface;
use BrokeYourBike\Bancore\Interfaces\ConfigInterface;
use BrokeYourBike\Bancore\Exceptions\PrepareRequestException;
use BrokeYourBike\Bancore\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class ValidateBankTransactionTest extends TestCase
{
    private string $token = 'secure-token';
    private string $sessionId = 'session-id';
    private SenderInterface $sender;
    private RecipientInterface $recipient;
    private IdentifierSourceInterface $identifierSource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sender = $this->getMockBuilder(SenderInterface::class)->getMock();
        $this->recipient = $this->getMockBuilder(RecipientInterface::class)->getMock();
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

        $api->validateBankTransaction($this->sessionId, $transaction);
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

        $api->validateBankTransaction($this->sessionId, $transaction);
    }

    /** @test */
    public function it_will_throw_if_no_identifier_source_in_transaction()
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getSender')->willReturn($this->sender);
        $transaction->method('getRecipient')->willReturn($this->recipient);

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

        $api->validateBankTransaction($this->sessionId, $transaction);
    }

    /** @test */
    public function it_can_prepare_request(): void
    {
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();
        $transaction->method('getSender')->willReturn($this->sender);
        $transaction->method('getRecipient')->willReturn($this->recipient);
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
                    "responseDescription": "Successful operation"
                }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/transactions/validations/bank-account',
            [
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$this->token}",
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'accountNumber' => $transaction->getRecipient()->getBankAccount(),
                    'bankCode' => $transaction->getRecipient()->getBankCode(),
                    'bankName' => $transaction->getRecipient()->getBankName(),
                    'countryCode' => $transaction->getRecipient()->getCountryCode(),
                    'identifier' => $transaction->getIdentifierSource()?->getCode(),
                    'senderName' => $transaction->getSender()->getName(),
                    'beneficiaryName' => $transaction->getRecipient()->getName(),
                    'mobileNumber' => $transaction->getRecipient()->getPhoneNumber(),
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

        $requestResult = $api->validateBankTransaction($this->sessionId, $transaction);

        $this->assertInstanceOf(ValidateBankTransactionResponse::class, $requestResult);
    }
}
