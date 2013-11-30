<?php
namespace Chameleon\Format;

use Alchemy\BinaryDriver\AbstractBinary;

class Docx
{

	private $inputFile;

	public function __construct($inputFile)
	{
		$this->inputFile = $inputFile;
	}

	public function getInputFile()
	{
		return $this->inputFile;
	}

	public function toPdf($outputFile)
	{
		// return exec('unoconv -f pdf -o ' . $outputFile . ' ' . $this->getInputFile());

		$driver = \Chameleon\Driver\UnoconvDriver::load('/usr/bin/unoconv');

		return $driver->command(array('-f', 'pdf', '-o', $outputFile, $this->getInputFile()));
 
		// return $this->getInputFile();
	}
}