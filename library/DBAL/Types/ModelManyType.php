<?php

/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.
 */

namespace Coast\Doctrine\DBAL\Types;

use Coast\Collection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types;

class ModelManyType extends Types\JsonArrayType
{
    const MODEL_MANY = 'coast_model_many';

    protected static $_parser;

    public static function parser($parser = null)
    {
        if (func_num_args() > 0) {
            self::$_parser = $parser;
        }

        return self::$_parser;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $value = parent::convertToPHPValue($value, $platform);
        if (isset($value)) {
            $items = $value;
            $value = new Collection;
            foreach ($items as $i => $item) {
                $class = $item['__CLASS__'];
                $data = $item;
                unset($data['__CLASS__']);
                $value[$i] = new $class;
                $value[$i]->fromArray($data);
            }
        }

        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (isset($value)) {
            $items = $value;
            $value = [];
            foreach ($items as $i => $item) {
                $value[$i] = $item->toArray(static::$_parser) + [
                    '__CLASS__' => get_class($item),
                ];
            }
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    public function getName()
    {
        return self::MODEL_MANY;
    }

    public function requiresSQLCommentHint(\Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return true;
    }
}
