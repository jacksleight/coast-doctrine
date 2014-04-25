<?php
/* 
 * Copyright 2014 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE. 
 */

namespace Coast\Doctrine\DBAL\Types;

class JsonType extends \Doctrine\DBAL\Types\Type
{
	const JSON = 'json';

	public function getSqlDeclaration(array $fieldDeclaration, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
	{
		return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
	}

	public function convertToPHPValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
	{
		return json_decode($value, true);
	}

	public function convertToDatabaseValue($value, \Doctrine\DBAL\Platforms\AbstractPlatform $platform)
	{
		return json_encode($value);
	}

	public function getName()
	{
		return self::JSON;
	}
}