<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;
use BrokeYourBike\ResolveUri\ResolveUriTrait;
use BrokeYourBike\HttpEnums\HttpMethodEnum;
use BrokeYourBike\HttpClient\HttpClientTrait;
use BrokeYourBike\HttpClient\HttpClientInterface;
use BrokeYourBike\HasSourceModel\SourceModelInterface;
use BrokeYourBike\HasSourceModel\HasSourceModelTrait;
use BrokeYourBike\Bancore\Models\ValidateBankTransactionResponse;
use BrokeYourBike\Bancore\Models\SendBankTransactionResponse;
use BrokeYourBike\Bancore\Models\QuoteBankTransactionResponse;
use BrokeYourBike\Bancore\Models\FetchMobileWalletsResponse;
use BrokeYourBike\Bancore\Models\FetchExhangeRatesResponse;
use BrokeYourBike\Bancore\Models\FetchBanksResponse;
use BrokeYourBike\Bancore\Models\FetchAuthTokenResponse;
use BrokeYourBike\Bancore\Interfaces\TransactionInterface;
use BrokeYourBike\Bancore\Interfaces\SenderInterface;
use BrokeYourBike\Bancore\Interfaces\RecipientInterface;
use BrokeYourBike\Bancore\Interfaces\QuotaInterface;
use BrokeYourBike\Bancore\Interfaces\IdentifierSourceInterface;
use BrokeYourBike\Bancore\Interfaces\ConfigInterface;
use BrokeYourBike\Bancore\Exceptions\PrepareRequestException;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class Client implements HttpClientInterface
{
    use HttpClientTrait;
    use ResolveUriTrait;
    use HasSourceModelTrait;

    private ConfigInterface $config;
    private CacheInterface $cache;

    public function __construct(ConfigInterface $config, ClientInterface $httpClient, CacheInterface $cache)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

    public function authTokenCacheKey(): string
    {
        return get_class($this) . ':authToken:';
    }

    public function getAuthToken(): string
    {
        if ($this->cache->has($this->authTokenCacheKey())) {
            $cachedToken = $this->cache->get($this->authTokenCacheKey());
            if (is_string($cachedToken)) {
                return $cachedToken;
            }
        }

        $response = $this->fetchAuthTokenRaw();

        $this->cache->set(
            $this->authTokenCacheKey(),
            $response->token,
            $response->expiresIn
        );

        return $response->token;
    }

    public function fetchAuthTokenRaw(): FetchAuthTokenResponse
    {
        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
            ],
            \GuzzleHttp\RequestOptions::JSON => [
                'username' => $this->config->getUsername(),
                'password' => $this->config->getPassword(),
                'rememberMe' => true,
            ],
        ];

        $uri = (string) $this->resolveUriFor($this->config->getUrl(), 'auth');

        $response = $this->httpClient->request(
            HttpMethodEnum::POST->value,
            $uri,
            $options
        );

        return new FetchAuthTokenResponse($response);
    }

    public function fetchBanksRaw(string $countryCode): FetchBanksResponse
    {
        $response = $this->performRequest(HttpMethodEnum::GET, "miscellaneous/banks/country/{$countryCode}", []);

        return new FetchBanksResponse($response);
    }

    public function fetchMobileWalletsRaw(string $countryCode): FetchMobileWalletsResponse
    {
        $response = $this->performRequest(HttpMethodEnum::GET, "miscellaneous/mobile-wallets/country/{$countryCode}", []);

        return new FetchMobileWalletsResponse($response);
    }

    public function fetchExhangeRatesForRaw(string $baseCurrencyCode): FetchExhangeRatesResponse
    {
        $response = $this->performRequest(HttpMethodEnum::GET, "transactions/exchange-rates/{$baseCurrencyCode}", []);

        return new FetchExhangeRatesResponse($response);
    }

    public function validateBankTransaction(string $sessionId, TransactionInterface $transaction): ValidateBankTransactionResponse
    {
        $sender = $transaction->getSender();
        $recipient = $transaction->getRecipient();
        $identifierSource = $transaction->getIdentifierSource();

        if (!$sender instanceof SenderInterface) {
            throw PrepareRequestException::noSender($transaction);
        }

        if (!$recipient instanceof RecipientInterface) {
            throw PrepareRequestException::noRecipient($transaction);
        }

        if (!$identifierSource instanceof IdentifierSourceInterface) {
            throw PrepareRequestException::noIdentifierSource($transaction);
        }

        if ($transaction instanceof SourceModelInterface) {
            $this->setSourceModel($transaction);
        }

        $response = $this->performRequest(HttpMethodEnum::POST, 'transactions/validations/bank-account', [
            'accountNumber' => (string) $recipient->getBankAccount(),
            'bankCode' => (string) $recipient->getBankCode(),
            'bankName' => (string) $recipient->getBankName(),
            'countryCode' => $recipient->getCountryCode(),
            'identifier' => $identifierSource->getCode(),
            'senderName' => $sender->getName(),
            'beneficiaryName' => $recipient->getName(),
            'mobileNumber' => $sender->getPhoneNumber(),
            'sessionId' => $sessionId,
        ]);

        return new ValidateBankTransactionResponse($response);
    }

    public function quoteBankTransaction(string $sessionId, TransactionInterface $transaction): QuoteBankTransactionResponse
    {
        $sender = $transaction->getSender();
        $recipient = $transaction->getRecipient();

        if (!$sender instanceof SenderInterface) {
            throw PrepareRequestException::noSender($transaction);
        }

        if (!$recipient instanceof RecipientInterface) {
            throw PrepareRequestException::noRecipient($transaction);
        }

        if ($transaction instanceof SourceModelInterface) {
            $this->setSourceModel($transaction);
        }

        $response = $this->performRequest(HttpMethodEnum::POST, 'transactions/quotations/bank-account', [
            'accountNumber' => (string) $recipient->getBankAccount(),
            'beneficiaryCountry' => $recipient->getCountryCode(),
            'beneficiaryCurrency' => $transaction->getReceiveCurrencyCode(),
            'beneficiaryMobileNumber' => $recipient->getPhoneNumber(),
            'senderAmount' => $transaction->getReceiveAmount(),
            'senderCurrency' => $transaction->getSendCurrencyCode(),
            'senderMobileNumber' => $sender->getPhoneNumber(),
            'sessionId' => $sessionId,
        ]);

        return new QuoteBankTransactionResponse($response);
    }

    public function sendBankTransaction(TransactionInterface $transaction): SendBankTransactionResponse
    {
        $sender = $transaction->getSender();
        $recipient = $transaction->getRecipient();
        $quota = $transaction->getQuota();
        $identifierSource = $transaction->getIdentifierSource();

        if (!$sender instanceof SenderInterface) {
            throw PrepareRequestException::noSender($transaction);
        }

        if (!$recipient instanceof RecipientInterface) {
            throw PrepareRequestException::noRecipient($transaction);
        }

        if (!$quota instanceof QuotaInterface) {
            throw PrepareRequestException::noQuota($transaction);
        }

        if (!$identifierSource instanceof IdentifierSourceInterface) {
            throw PrepareRequestException::noIdentifierSource($transaction);
        }

        if ($transaction instanceof SourceModelInterface) {
            $this->setSourceModel($transaction);
        }

        $response = $this->performRequest(HttpMethodEnum::POST, 'transactions/remittances/bank-account', [
            'sessionId' => $transaction->getReference(),
            'identifier' => $identifierSource->getCode(),
            'quoteId' => $quota->getReference(),
            'accountNumber' => (string) $recipient->getBankAccount(),
            'bankCode' => (string) $recipient->getBankCode(),
            'beneficiaryAmount' => $transaction->getReceiveAmount(),
            'beneficiaryCountry' => $recipient->getCountryCode(),
            'beneficiaryCurrency' => $transaction->getReceiveCurrencyCode(),
            'beneficiaryDetails' => [
                'fullName' => $recipient->getName(),
            ],
            'beneficiaryMobileNumber' => $recipient->getPhoneNumber(),
            'senderCurrency' => $transaction->getSendCurrencyCode(),
            'senderDetails' => [
                'fullName' => $sender->getName(),
                'address' => [
                    'country' => $sender->getCountryCode(),
                ],
            ],
            'senderMobileNumber' => $sender->getPhoneNumber(),
            'description' => $transaction->getReference(),
        ]);

        return new SendBankTransactionResponse($response);
    }

    /**
     * @param HttpMethodEnum $method
     * @param string $uri
     * @param array<mixed> $data
     * @return ResponseInterface
     */
    private function performRequest(HttpMethodEnum $method, string $uri, array $data): ResponseInterface
    {
        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$this->getAuthToken()}",
            ],
            \GuzzleHttp\RequestOptions::JSON => $data,
        ];

        if ($this->getSourceModel()) {
            $options[\BrokeYourBike\HasSourceModel\Enums\RequestOptions::SOURCE_MODEL] = $this->getSourceModel();
        }

        $uri = (string) $this->resolveUriFor($this->config->getUrl(), $uri);
        return $this->httpClient->request($method->value, $uri, $options);
    }
}
