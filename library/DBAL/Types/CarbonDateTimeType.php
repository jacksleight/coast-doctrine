<?php
/* 
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types,
    Doctrine\DBAL\Platforms\AbstractPlatform;

class CarbonDateTimeType extends Types\DateTimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        return isset($value)
            ? \Carbon\Carbon::instance($value)
            : $value;
    }
}