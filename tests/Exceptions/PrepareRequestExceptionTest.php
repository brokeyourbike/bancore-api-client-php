<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Tests\Enums;

use PHPUnit\Framework\TestCase;
use BrokeYourBike\Bancore\Interfaces\TransactionInterface;
use BrokeYourBike\Bancore\Exceptions\PrepareRequestException;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class PrepareRequestExceptionTest extends TestCase
{
    /** @test */
    public function it_can_return_transaction()
    {
        /** @var TransactionInterface $transaction */
        $transaction = $this->getMockBuilder(TransactionInterface::class)->getMock();

        $exception = PrepareRequestException::noSender($transaction);

        $this->assertSame($transaction, $exception->getTransaction());
    }
}
