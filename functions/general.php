<?php
/* 
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine;

function register_dbal_types()
{
    $types = [
        'coast_url'        => 'Coast\Doctrine\DBAL\Types\UrlType',
        'coast_file'       => 'Coast\Doctrine\DBAL\Types\FileType',
        'coast_model_one'  => 'Coast\Doctrine\DBAL\Types\ModelOneType',
        'coast_model_many' => 'Coast\Doctrine\DBAL\Types\ModelManyType',
    ];
    foreach ($types as $name => $class) {
        \Doctrine\DBAL\Types\Type::addType($name, $class);
    }
    
    $types = [
        'date'     => 'Coast\Doctrine\DBAL\Types\DateType',
        'time'     => 'Coast\Doctrine\DBAL\Types\TimeType',
        'datetime' => 'Coast\Doctrine\DBAL\Types\DateTimeType',
    ];
    foreach ($types as $name => $class) {
        \Doctrine\DBAL\Types\Type::overrideType($name, $class);
    }
}