<?php
namespace Chameleon\Exception;

class UnsupportedConversionException extends \Exception
{

	public function __construct($formatFrom, $formatTo)
	{
		parent::__construct('Cannot convert from ' . $formatFrom . ' to ' . $formatTo);
	}
}