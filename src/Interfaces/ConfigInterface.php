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
interface ConfigInterface
{
    public function isLive(): bool;
    public function getUrl(): string;
    public function getUsername(): string;
    public function getPassword(): string;

    /**
     * Sender phone number is required,
     * and it's good to have a default value
     *
     * @return string
     */
    public function getDefaultSenderPhoneNumber(): string;

    /**
     * Recipient phone number is required
     * and it's good to have a default value
     *
     * @return string
     */
    public function getDefaultRecipientPhoneNumber(): string;
}
