<?php

namespace App\Exceptions\Bancore;

use BrokeYourBike\Bancore\Interfaces\TransactionInterface;
use BrokeYourBike\Bancore\Interfaces\SenderInterface;
use BrokeYourBike\Bancore\Interfaces\RecipientInterface;
use BrokeYourBike\Bancore\Interfaces\QuotaInterface;
use BrokeYourBike\Bancore\Interfaces\IdentifierSourceInterface;

final class PrepareRequestException extends \RuntimeException
{
    public static function noSender(TransactionInterface $transaction): self
    {
        $className = SenderInterface::class;
        return new static("{$className} is required for `{$transaction->getReference()}`");
    }

    public static function noRecipient(TransactionInterface $transaction): self
    {
        $className = RecipientInterface::class;
        return new static("{$className} is required for `{$transaction->getReference()}`");
    }

    public static function noQuota(TransactionInterface $transaction): self
    {
        $className = QuotaInterface::class;
        return new static("{$className} is required for `{$transaction->getReference()}`");
    }

    public static function noIdentifierSource(TransactionInterface $transaction): self
    {
        $className = IdentifierSourceInterface::class;
        return new static("{$className} is required for `{$transaction->getReference()}`");
    }
}
