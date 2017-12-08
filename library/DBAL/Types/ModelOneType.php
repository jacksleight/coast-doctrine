<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\DBAL\Types;

use Doctrine\DBAL\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class ModelOneType extends Types\JsonArrayType
{
    const MODEL_ONE = 'coast_model_one';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        if (isset($value)) {
            $class = $value['__CLASS__'];
            $data  = $value;
            unset($data['__CLASS__']);
            $value = new $class();
            $value->fromArray($data);
        }
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (isset($value)) {
            $value = $value->toArray() + [
                '__CLASS__' => get_class($value),
            ];
        }
        return parent::convertToDatabaseValue($value, $platform);
    }

    public function getName()
    {
        return self::MODEL_ONE;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}