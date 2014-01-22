<?php
namespace Chameleon;

use Dflydev\ApacheMimeTypes\PhpRepository;
use Dflydev\ApacheMimeTypes\RepositoryInterface;

/**
 * Class Convert
 * @package Chameleon
 */
class Convert
{

    /**
     * @var string
     */
    private $inputFile;
    /**
     * @var string
     */
    private $outputFile;
    /**
     * @var string
     */
    private $formatTo;
    /**
     * @var RepositoryInterface
     */
    private $mimeRepository;

    /**
     * @param string $inputFile
     * @param string $outputFile
     * @param string $formatTo
     */
    public function __construct($inputFile = null, $outputFile = null, $formatTo = null)
    {
        $this->inputFile = $inputFile;
        $this->outputFile = $outputFile;
        $this->formatTo = $formatTo;

        $this->mimeRepository = new PhpRepository();
    }

    /**
     * @return string
     */
    public function getInputFile()
    {
        return $this->inputFile;
    }

    /**
     * @param string $inputFile
     */
    public function setInputFile($inputFile)
    {
        $this->inputFile = $inputFile;
    }

    /**
     * @return string
     */
    public function getOutputFile()
    {
        return $this->outputFile;
    }

    /**
     * @param string $outputFile
     */
    public function setOutputFile($outputFile)
    {
        $this->outputFile = $outputFile;
    }

    /**
     * @return string
     */
    public function getFormatTo()
    {
        return $this->formatTo;
    }

    /**
     * @param string $formatTo
     */
    public function setFormatTo($formatTo)
    {
        $this->formatTo = $formatTo;
    }

    /**
     * @return mixed
     * @throws Exception\UnsupportedConversionException
     */
    public function convert()
    {
        if (!$this->getFormatTo()) {
            $this->setFormatTo(pathinfo($this->getOutputFile(), PATHINFO_EXTENSION));
        }

        $formatFrom = $this->detectFormatFrom();
        $formatFromClassName = 'Chameleon\Format\\' . $formatFrom;
        $formatToFunctionName = 'to' . ucfirst($this->getFormatTo());

        $conversionClass = new $formatFromClassName($this->getInputFile(), $this->getOutputFile());

        if (method_exists($conversionClass, $formatToFunctionName) && is_callable(
                array($conversionClass, $formatToFunctionName)
            )
        ) {
            return $conversionClass->$formatToFunctionName();
        } else {
            throw new Exception\UnsupportedConversionException($formatFrom, $this->getFormatTo());
        }
    }

    /**
     * @return string
     */
    public function detectFormatFrom()
    {
        $mimeType = $this->detectMimeType($this->getInputFile());

        $extension = $this->findExtensionFromMimeType($mimeType);

        return ucfirst($extension);
    }

    /**
     * @param string $mimeType
     * @throws \ErrorException
     * @return string
     */
    public function findExtensionFromMimeType($mimeType)
    {
        $extension = current($this->mimeRepository->findExtensions($mimeType));

        if (!$extension) {
            throw new \ErrorException('Mime could not be mapped to an extension. Consider submitting a GitHub issue!');
        }

        return $extension;
    }

    /**
     * @param string $filename
     * @return string
     * @throws \ErrorException
     */
    public function detectMimeType($filename)
    {
        // Is file readable ?
        if (false === stream_resolve_include_path($filename)) {
            throw new \ErrorException('Could not detect mime type of file (file not readable): ' . $filename);
        }

        if (!isset($finfo)) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
        }

        if (!empty($finfo)) {
            $type = finfo_file($finfo, $filename);
        }

        if (!isset($type)) {
            throw new \ErrorException('Could not detect mime type of file: ' . $filename);
        } else {
            return $type;
        }
    }
}