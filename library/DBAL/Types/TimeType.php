<?php
/* 
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\DBAL\Types;

use Coast\DateTime;
use Doctrine\DBAL\Types,
    Doctrine\DBAL\Platforms\AbstractPlatform;

class TimeType extends Types\TimeType
{
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        if (isset($value)) {
            $value = new DateTime($value->format('Y-m-d H:i:s.u'), $value->getTimezone());
            $value->mode(DateTime::MODE_TIME);
        }
        return $value;
    }
}