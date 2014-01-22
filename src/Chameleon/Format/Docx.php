<?php
namespace Chameleon\Format;

use Alchemy\BinaryDriver\Exception\ExecutionFailureException;
use Chameleon\Driver\UnoconvDriver;

/**
 * Class Docx
 * @package Chameleon\Format
 */
class Docx extends Base
{

    /**
     * @param string $inputFile
     * @param string $outputFile
     */
    public function __construct($inputFile, $outputFile)
    {
        $this->inputFile = $inputFile;
        $this->outputFile = $outputFile;
        $this->savedFiles = array();
    }

    /**
     * @return array
     */
    public function toGif()
    {
        return $this->documentToImage($this->inputFile, $this->getOutputFilename(), 'gif');
    }

    /**
     * @return array
     */
    public function toJpg()
    {
        return $this->documentToImage($this->inputFile, $this->getOutputFilename(), 'jpg');
    }

    /**
     * @return string
     */
    public function toPdf()
    {
        $driver = UnoconvDriver::load('/usr/bin/unoconv');

        try {
            $driver->command(array('-f', 'pdf', '-o', $this->outputFile, $this->inputFile));

            $this->savedFiles[] = $this->outputFile;
        } catch (ExecutionFailureException $e) {
            // TODO: Implement this
            var_dump($e->getMessage());
        }

        return $this->savedFiles;
    }

    /**
     * @return array
     */
    public function toPng()
    {
        return $this->documentToImage($this->inputFile, $this->getOutputFilename(), 'png');
    }
}