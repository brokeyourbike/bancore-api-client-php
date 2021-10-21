<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\Bancore\Enums;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 *
 * @method static GenderTypeEnum MALE()
 * @method static GenderTypeEnum FEMALE()
 * @psalm-immutable
 */
final class GenderTypeEnum extends \MyCLabs\Enum\Enum
{
    private const MALE = 'M';
    private const FEMALE = 'F';
}
