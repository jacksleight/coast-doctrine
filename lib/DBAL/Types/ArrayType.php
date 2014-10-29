<?php
/* 
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\DBAL\Types;

class ArrayType extends \Doctrine\DBAL\Types\ArrayType
{
    const TARRAY = 'coast_array';

    public function convertToPHPValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return isset($value)
        	? json_decode($value, true)
        	: null;
    }

    public function convertToDatabaseValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return isset($value)
        	? json_encode($value)
        	: null;
    }

    public function getName()
    {
        return self::TARRAY;
    }
}