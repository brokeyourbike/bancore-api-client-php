<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Exceptions;

use BrokeYourBike\Bancore\Interfaces\TransactionInterface;
use BrokeYourBike\Bancore\Interfaces\SenderInterface;
use BrokeYourBike\Bancore\Interfaces\RecipientInterface;
use BrokeYourBike\Bancore\Interfaces\QuotaInterface;
use BrokeYourBike\Bancore\Interfaces\IdentifierSourceInterface;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 */
final class PrepareRequestException extends \RuntimeException
{
    private TransactionInterface $transaction;

    protected function __construct(TransactionInterface $transaction, string $message, ?\Throwable $previous = null)
    {
        $this->transaction = $transaction;

        parent::__construct($message, 0, $previous);
    }

    public function getTransaction(): TransactionInterface
    {
        return $this->transaction;
    }

    public static function noSender(TransactionInterface $transaction): self
    {
        $className = $transaction::class;
        $senderClassName = SenderInterface::class;
        return new static($transaction, "{$senderClassName} is required for {$className} `{$transaction->getReference()}`");
    }

    public static function noRecipient(TransactionInterface $transaction): self
    {
        $className = $transaction::class;
        $recipientClassName = RecipientInterface::class;
        return new static($transaction, "{$recipientClassName} is required for {$className} `{$transaction->getReference()}`");
    }

    public static function noQuota(TransactionInterface $transaction): self
    {
        $className = $transaction::class;
        $quotaClassName = QuotaInterface::class;
        return new static($transaction, "{$quotaClassName} is required for {$className} `{$transaction->getReference()}`");
    }

    public static function noIdentifierSource(TransactionInterface $transaction): self
    {
        $className = $transaction::class;
        $identifierSourceClassName = IdentifierSourceInterface::class;
        return new static($transaction, "{$identifierSourceClassName} is required for {$className} `{$transaction->getReference()}`");
    }
}
