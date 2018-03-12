<?php

namespace ETL\Models;

use DOMDocument;
use ETL\Support\Contracts\LoaderInterface;

class Loader implements LoaderInterface
{
    protected $filePath = '';

    protected $transformedData = [];
    protected $headingFields = [];
    protected $outputFileName = '';

    public function load($type = 'json', $transformedData = [], $headingFields = [], $outputFileName = '', $filePath = '')
    {
        $this->transformedData = $transformedData;
        $this->headingFields = array_map('strtolower', $headingFields);
        $this->outputFileName = $outputFileName;

        $this->filePath = ($filePath == '') ? './data_storage/' : $filePath;

        switch ($type) {
            case 'xml' :
                $xml = new DOMDocument('1.0', 'UTF-8');

                return $this->xmlOutput($xml);
            case 'json':
                return $this->jsonOutput();
            default:
                throw new \Exception('Unsupported format!');
        }
    }

    protected function jsonOutput()
    {
        $outputFileName = 'json_'.$this->outputFileName.'.json';
        if (file_exists($this->filePath.$outputFileName)) {
            // this output request already exist
            return file_get_contents($this->filePath.$outputFileName);
        }
        $data = $this->transformedData;

        $jsonArray = [];
        foreach ($data as $row) {
            $jsonArray[] = array_combine($this->headingFields, $row);
        }
        $data = json_encode($jsonArray);
        file_put_contents($this->filePath.$outputFileName, $data);

        return file_get_contents($this->filePath.$outputFileName);
    }

    protected function xmlOutput(DOMDocument $xml)
    {
        $outputFileName = 'xml_'.$this->outputFileName.'.xml';
        if (file_exists($this->filePath.$outputFileName)) {
            // this output request already exist
            return file_get_contents($this->filePath.$outputFileName);
        }
        $data = $this->transformedData;

        $jsonArray = [];
        $root = $xml->createElement('data');
        foreach ($data as $row) {
            $xml_row = $xml->createElement('row');
            foreach ($this->headingFields as $key => $fieldName) {
                $xml_field = $xml->createElement($fieldName, $row[$key]);
                $xml_row->appendChild($xml_field);
            }
            $root->appendChild($xml_row);
        }
        $xml->appendChild($root);
        $xml->save($this->filePath.$outputFileName);

        return file_get_contents($this->filePath.$outputFileName);
    }
}
