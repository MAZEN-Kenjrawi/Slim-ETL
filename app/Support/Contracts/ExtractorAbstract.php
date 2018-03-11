<?php

namespace ETL\Support\Contracts;

use IteratorAggregate;

abstract class ExtractorAbstract implements IteratorAggregate
{
    protected $config = [];

    protected $has_heading = true;
    protected $heading_fields = [];

    protected $data = [];
    protected $filters = [];

    public function getData() {}

    public function getArgumentsHash() {}

    public function getHeading() {
        return $this->heading_fields;
    }

    public function getCountOfData() {
        return count($this->data);
    }

    /**
     * return Array, of filtered $row
     * called on every single row of CSV file while reading it, before store it in $this->data
     * apply deticated filters on every cell of the column $key
     * filters are passed through $config array on the __construct()
     */
    protected function processFilters($row) {
        if(empty($this->filters)) {
            return $row;
        }        
        foreach($this->filters as $rowKey => $rowFilters) {            
            $rowFiltersArray = explode('|', $rowFilters);            
            $realKey = $this->getFieldKey($rowKey);
            if(!isset($row[$realKey])) {
                throw new \Exception('Field ('.$rowKey.') does not exist!');
            }
            foreach($rowFiltersArray as $filter) {                
                if (function_exists(strtolower($filter))) {
                    # Filter using customized function
                    $row[$realKey] = strtolower($filter)($row[$realKey]);
                } else {
                    throw new \Exception('Filter ('.$filter.') does not exist!');
                }
            }
        }

        return $row;
    }

    public function getIterator() {
		return new ArrayIterator($this->data);
    }

    protected function getFieldKey($field) {
        $sort_by = $field;
        if(!is_numeric($field) && !empty($this->heading_fields)) {
            # The field is not numeric and there is heading fields
            $field_key = array_intersect(array_map('strtolower', $this->heading_fields), [strtolower($field)]);            
            $sort_by = key($field_key);
        }
        return $sort_by;
    }
}