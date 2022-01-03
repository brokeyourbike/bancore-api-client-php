<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Models;

use BrokeYourBike\DataTransferObject\JsonResponse;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class FetchAuthTokenResponse extends JsonResponse
{
    public string $uuid;
    public string $username;
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $token;
    public int $expiresIn;
    public string $lastLoggedIn;
    public string $createdAt;
}
