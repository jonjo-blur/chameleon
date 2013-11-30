<?php
namespace Chameleon;

class Convert
{

	private $inputFile;
	private $outputFile;
	private $formatFrom;
	private $formatTo;

	public function __construct($inputFile = null, $outputFile = null, $formatFrom = null, $formatTo = null)
	{
		$this->inputFile = $inputFile;
		$this->outputFile = $outputFile;
		$this->formatFrom = $formatFrom;
		$this->formatTo = $formatTo;
	}

	public function getInputFile()
	{
		return $this->inputFile;
	}

	public function setInputFile($inputFile)
	{
		$this->inputFile = $inputFile;
	}

	public function getOutputFile()
	{
		return $this->outputFile;
	}

	public function setOutputFile($outputFile)
	{
		$this->outputFile = $outputFile;
	}

	public function getFormatFrom()
	{
		return $this->formatFrom;
	}

	public function setFormatFrom($formatFrom)
	{
		$this->formatFrom = $formatFrom;
	}

	public function getFormatTo()
	{
		return $this->formatTo;
	}

	public function setFormatTo($formatTo)
	{
		$this->formatTo = $formatTo;
	}

	public function convert()
	{
		$formatFromClassName = 'Chameleon\Format\\' . ucfirst($this->getFormatFrom());
		$formatToFunctionName = 'to' . ucfirst($this->getFormatTo());

		$conversionClass = new $formatFromClassName($this->getInputFile());

		if (method_exists($conversionClass, $formatToFunctionName) && is_callable(array($conversionClass, $formatToFunctionName))) {
			return $conversionClass->$formatToFunctionName($this->getOutputFile());
		} else {
			throw new Exception\UnsupportedConversionException($this->getFormatFrom(), $this->getFormatTo());
		}		
	}
}