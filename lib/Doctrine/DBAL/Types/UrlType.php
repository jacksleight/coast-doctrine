<?php
/* 
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\DBAL\Types;

class UrlType extends \Doctrine\DBAL\Types\Type
{
	const URL = 'url';

	public function getSqlDeclaration(array $fieldDeclaration, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
	{
		return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
	}

	public function convertToPHPValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
	{
		return isset($value)
			? new \Coast\Url($value)
			: null;
	}

	public function convertToDatabaseValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
	{
		return isset($value)
			? $value->toString()
			: null;
	}

	public function getName()
	{
		return self::URL;
	}
}