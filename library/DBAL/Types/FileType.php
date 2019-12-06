<?php
/* 
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\DBAL\Types;

use Coast\File;
use Coast\Dir;

class FileType extends \Doctrine\DBAL\Types\Type
{
    const FILE = 'coast_file';

    protected static $_baseDir;

    public static function baseDir(Dir $baseDir)
    {
        if (func_num_args() > 0) {
            self::$_baseDir = $baseDir;
        }
        return self::$_baseDir;
    }

    public function getSqlDeclaration(array $fieldDeclaration, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        if (!isset($value)) {
            return null;
        }
        $value = new File($value);
        if (isset(self::$_baseDir)) {
            $value = $value->toAbsolute(self::$_baseDir);
        }
        return $value;
    }

    public function convertToDatabaseValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        if (!isset($value)) {
            return null;
        }
        if (isset(self::$_baseDir)) {
            $value = $value->toRelative(self::$_baseDir);
        }
        return $value->toString();
    }

    public function getName()
    {
        return self::FILE;
    }

    public function requiresSQLCommentHint(\Doctrine\DBAL\Platforms\AbstractPlatform $platform)
    {
        return true;
    }
}