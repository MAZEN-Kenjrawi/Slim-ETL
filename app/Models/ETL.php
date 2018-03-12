<?php

namespace ETL\Models;

use ETL\Support\Contracts\ExtractorInterface;
use ETL\Support\Contracts\LoaderInterface;
use ETL\Support\Contracts\TransformerInterface;

class ETL
{
    /**
     * The Extractor.
     *
     * @var \ETL\Models\ExtractorCSV
     */
    protected $Extractor;

    /**
     * The Transformer.
     *
     * @var \ETL\Models\Transformer
     */
    protected $Transformer;

    /**
     * The Loader.
     *
     * @var \ETL\Models\Loader
     */
    protected $Loader;

    protected $data = [];
    protected $extractingConfig = [];
    protected $sortingBy = [];
    protected $outputType = '';

    protected $outputFilePath = '';
    protected $destination = '';

    public function __construct(ExtractorInterface $Extractor, TransformerInterface $Transformer, LoaderInterface $Loader)
    {
        $this->Extractor = $Extractor;
        $this->Transformer = $Transformer;
        $this->Loader = $Loader;
    }

    /**
     * Get the generated data.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Extract data from the given CSV source.
     *
     * @param string $filePath
     * @param array  $config
     *
     * @return $this
     */
    public function extract($config = [])
    {
        $this->extractingConfig = $config;
        $this->Extractor->extract($this->extractingConfig);

        return $this;
    }

    /**
     * Execute a transformation.
     *
     * @param array $sortingBy
     *
     * @return $this
     */
    public function transform($sortingBy = [])
    {
        if (count($sortingBy) > 0) {
            $this->sortingBy = $sortingBy;
        }

        return $this;
    }

    /**
     * Load data to the given destination.
     *
     * @param string $destination
     * @param array  $options
     *
     * @return $this
     */
    public function load($type = 'json', $destination = '')
    {
        $this->outputType = $type;
        $this->destination = $destination;
        $this->setOutputPath();

        return $this;
    }

    /**
     * Return output file name path.
     *
     * @return string
     */
    public function getResponse()
    {
        if (!file_exists($this->outputFilePath)) {
            $this->data = $this->Extractor->getData();
            $this->headingFields = $this->Extractor->getHeading();

            $this->Transformer = $this->Transformer->transform($this->data, $this->headingFields);
            foreach ($this->sortingBy as $field => $order) {
                $this->Transformer->sort_by($field, $order);
            }
            $this->data = $this->Transformer->getSortedData();

            $this->Loader = $this->Loader->load($this->outputType, $this->data, $this->headingFields, $this->getArgumentsHash(), $this->destination);
        }

        return $this->outputFilePath;
    }

    /**
     * Setting the output file name.
     *
     * @return $this
     */
    protected function setOutputPath()
    {
        $this->outputFilePath = $this->destination.$this->outputType.'_'.$this->getArgumentsHash().'.'.$this->outputType;

        return $this;
    }

    public function getArgumentsHash()
    {
        return md5($this->Extractor->getArgumentsHash().md5(serialize($this->sortingBy)));
    }
}
