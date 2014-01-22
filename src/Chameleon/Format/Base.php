<?php
namespace Chameleon\Format;

/**
 * Class Base
 * @package Chameleon\Format
 */
abstract class Base
{

    /**
     * @var string
     */
    protected $inputFile;
    /**
     * @var string
     */
    protected $outputFile;
    /**
     * @var array
     */
    protected $savedFiles;

    /**
     * @return string
     */
    public function getOutputFilename()
    {
        return pathinfo($this->outputFile, PATHINFO_DIRNAME) . '/' . pathinfo($this->outputFile, PATHINFO_FILENAME);
    }

    /**
     * @return string
     */
    public function generateTemporaryFile()
    {
        $metadata = stream_get_meta_data(tmpfile());

        return $metadata['uri'];
    }

    public function documentToImage($inputFile, $outputFileName, $outputExtension)
    {
        $callingClass = get_called_class();

        $temporaryFile = $this->generateTemporaryFile();
        $initial = new $callingClass($this->inputFile, $temporaryFile);
        $initial->toPdf();

        $imagick = new \Imagick();
        $imagick->readimage($temporaryFile);

        for ($i = 0; $i < $imagick->getnumberimages(); $i++) {
            $imagick->setiteratorindex($i);

            if ($imagick->writeimage($outputFileName . $i . '.' . $outputExtension)) {
                $this->savedFiles[] = $outputFileName . $i . '.' . $outputExtension;
            }
        }

        return $this->savedFiles;
    }
}