<?php
/* 
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine;

function register_dbal_types()
{
    $types = [
        'coast_array' => 'Coast\Doctrine\DBAL\Types\ArrayType',
        'coast_url'   => 'Coast\Doctrine\DBAL\Types\UrlType',
    ];
    foreach ($types as $name => $class) {
        \Doctrine\DBAL\Types\Type::addType($name, $class);
    }
    if (class_exists('Carbon\Carbon')) {
        $types = [
            'date'     => 'Coast\Doctrine\DBAL\Types\CarbonDateType',
            'time'     => 'Coast\Doctrine\DBAL\Types\CarbonTimeType',
            'datetime' => 'Coast\Doctrine\DBAL\Types\CarbonDateTimeType',
        ];
        foreach ($types as $name => $class) {
            \Doctrine\DBAL\Types\Type::overrideType($name, $class);
        }
    }
}