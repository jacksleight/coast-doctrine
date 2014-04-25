<?php
namespace Coast\Doctrine;

function register_dbal_types()
{
	$types = [
		'json'		=> 'Coast\Doctrine\DBAL\Types\JSONType',
		'url'		=> 'Coast\Doctrine\DBAL\Types\URLType',
	];
	foreach ($types as $name => $class) {
		\Doctrine\DBAL\Types\Type::addType($name, $class);
	}
	if (class_exists('Carbon\Carbon')) {
		$types = [
			'date'		=> 'Coast\Doctrine\DBAL\Types\CarbonDateType',
			'time'		=> 'Coast\Doctrine\DBAL\Types\CarbonTimeType',
			'datetime'	=> 'Coast\Doctrine\DBAL\Types\CarbonDateTimeType',
		];
		foreach ($types as $name => $class) {
			\Doctrine\DBAL\Types\Type::overrideType($name, $class);
		}
	}
}