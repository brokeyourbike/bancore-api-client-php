<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Interfaces;

use BrokeYourBike\Bancore\Interfaces\SenderInterface;
use BrokeYourBike\Bancore\Interfaces\RecipientInterface;
use BrokeYourBike\Bancore\Interfaces\QuotaInterface;
use BrokeYourBike\Bancore\Interfaces\IdentifierSourceInterface;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 */
interface TransactionInterface
{
    public function getSender(): ?SenderInterface;
    public function getRecipient(): ?RecipientInterface;
    public function getQuota(): ?QuotaInterface;
    public function getIdentifierSource(): ?IdentifierSourceInterface;
    public function getReference(): string;
    public function getSendCurrencyCode(): string;
    public function getReceiveCurrencyCode(): string;
    public function getSendAmount(): float;
    public function getReceiveAmount(): float;
}
