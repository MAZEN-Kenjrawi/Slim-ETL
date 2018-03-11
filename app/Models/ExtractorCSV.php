<?php
namespace ETL\Models;

use ETL\Support\Contracts\ExtractorAbstract;
use ETL\Support\Contracts\ExtractorInterface;

class ExtractorCSV extends ExtractorAbstract implements ExtractorInterface {

    protected $config = [];

    protected $delimiter = ',';
    protected $enclosure = '"';

    protected $has_heading = true;
    protected $heading_fields = [];

    protected $filePath = false;
    protected $fileHandle = false;

    protected $data = [];
    protected $filters = [];
    protected $columnCount = 0;

    public function __destruct() {
        if ($this->fileHandle) {
            fclose($this->fileHandle);
        }
    }
    
    public function extract($config = []) {
        if(isset($config['file_path']) && !file_exists($config['file_path'])) {
            throw new \Exception('File dose not exist!');
        }
        $this->config = $config;
        $this->filePath = $config['file_path'];

        $csvFileObject = new \SplFileObject($this->filePath);
        list($delimiter, $enclosure) = $csvFileObject->getCsvControl();
        $this->delimiter = ((!empty($delimiter))? $delimiter : $this->delimiter);
        $this->enclosure = ((!empty($enclosure))? $enclosure : $this->enclosure);
        
        // NOTE: this attempts to properly recognize line endings when reading files from Mac; has small performance penalty
        ini_set('auto_detect_line_endings', TRUE);
        $this->fileHandle = fopen($this->filePath, 'r');        

        $this->filters = (isset($config['filters'])? $config['filters'] : []);
        $this->has_heading = (isset($config['has_heading'])? $config['has_heading'] : true); # true by default

        return $this;
    }

    /** 
     * Fetch CSV File Rows, store it in $this->data[]
     * apply filters while fetching the rows, and set the heading
     */
    public function fetchCSVRows() {        
        if(!$this->fileHandle) {
            throw new \Exception('Unable to open file!');
        }
        while(($row = fgetcsv($this->fileHandle, 4096, $this->delimiter, $this->enclosure)) !== FALSE) {            
            if(count($row) > $this->columnCount) {
                $this->columnCount = count($row);
            }
            if(empty($this->heading_fields)) {
                $this->setHeadingFields($row);
                continue;
            }
            $this->data[] = $this->processFilters($row);
        }
        ini_set('auto_detect_line_endings', FALSE);
        return $this->data;
    }

    private function setHeadingFields($row) {
        # Don't Apply Filters on Header's Values
        if($this->has_heading) {
            $this->heading_fields = $row;
        } else {
            $this->data[] = $row;
            # let the heading to be ['row_1', 'row_2', ...]
            $this->heading_fields = array_map(function($singleHead){
                return 'row_'.$singleHead;
            }, range(0, count($row) - 1));
        }
    }

    public function getData() {
        $this->fetchCSVRows();
        return $this->data;
    }

    public function getHeading() {
        return $this->heading_fields;
    }

    public function getCountOfData() {
        return count($this->data);
    }

    public function getArgumentsHash() {
        # File name is a hash of 
        # (csv data source file last time modified + serialized config)
        return md5(filemtime($this->filePath).serialize($this->config));
    }
}