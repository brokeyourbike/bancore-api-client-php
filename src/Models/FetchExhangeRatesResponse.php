<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Models;

use Spatie\DataTransferObject\Casters\ArrayCaster;
use Spatie\DataTransferObject\Attributes\CastWith;
use BrokeYourBike\DataTransferObject\JsonResponse;
use BrokeYourBike\Bancore\Models\ExchangeRate;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class FetchExhangeRatesResponse extends JsonResponse
{
    public string $requestDate;
    public string $requestCurrency;
    public string $quotationStatus;

    /** @var ExchangeRate[] */
    #[CastWith(ArrayCaster::class, itemType: ExchangeRate::class)]
    public array $quotes;
}
