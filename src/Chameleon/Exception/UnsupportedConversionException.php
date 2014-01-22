<?php
namespace Chameleon\Exception;

/**
 * Class UnsupportedConversionException
 * @package Chameleon\Exception
 */
class UnsupportedConversionException extends \Exception
{

    /**
     * @param string $formatFrom
     * @param string $formatTo
     */
    public function __construct($formatFrom, $formatTo)
	{
		parent::__construct('Cannot convert from ' . ucfirst($formatFrom) . ' to ' . ucfirst($formatTo));
	}
}