<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Interfaces;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 */
interface RecipientInterface
{
    public function getName(): string;
    public function getPhoneNumber(): string;
    public function getCountryCode(): string;
    public function getBankAccount(): ?string;
    public function getBankCode(): ?string;
    public function getBankName(): ?string;
}
