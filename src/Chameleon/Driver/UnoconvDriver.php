<?php
namespace Chameleon\Driver;

use Alchemy\BinaryDriver\AbstractBinary;

/**
 * Class UnoconvDriver
 * @package Chameleon\Driver
 */
class UnoconvDriver extends AbstractBinary
{

    /**
     * Returns the name of the driver
     *
     * @return string
     */
    public function getName()
	{
		return 'unoconv driver';
	}
}