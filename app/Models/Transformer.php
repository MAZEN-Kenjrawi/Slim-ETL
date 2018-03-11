<?php
namespace ETL\Models;

use ETL\Support\Contracts\TransformerInterface;

class Transformer implements TransformerInterface{

    protected $data = [];
    protected $headingFields = [];

    protected $sorting_by = [];

    public function transform($data = [], $headingFields = []) {
        $this->data = $data;
        $this->headingFields = $headingFields;

        return $this;
    }

    public function sort_by($field = null, $order = null) {
        if($field === null || (!is_numeric($field) && empty($this->headingFields))) {
            return false;
        }

        if(is_array($field)) {
            # Prevent calling the function twice if the array contains single item
            if(count($field) > 1) {
                foreach($field as $sort) {
                    $this->sort_by($sort, $order);
                }
                return true;                
            }
            $field = array_pop($field);
        }

        $order_by = 'ASC';
        if(in_array(strtolower($order), ['0', 'desc', 'down'])) {
            $order_by = 'DESC';
        }
        $sort_by = $this->getFieldKey($field);

        if(is_numeric($sort_by) && $sort_by >= 0 && $sort_by < count($this->headingFields)) {
            $this->sorting_by[$sort_by] = $order_by;
        }
    }

    /**
     * return Array, of sorting_by
     * which define desired sorting creiteria
     */
    public function getSortingBy() {
        return $this->sorting_by;
    }

    /**
     * return Array, of sorted $this->data
     */
    public function getSortedData() {
        $data = $this->data;
        if(empty($data)) {
            # data empty, return empty array
            return [];
        }

        $sortingByArr = $this->getSortingBy();
        if(count($sortingByArr) > 0) {
            # There are sorting array
            foreach($sortingByArr as $key => $order) {
                usort($data, sort_by_strnatcmp($key, $order));
            }
        }

        return $data;
    }

    public function getIterator() {
		return new ArrayIterator($this->data);
    }

    private function getFieldKey($field) {
        $sort_by = $field;
        if(!is_numeric($field) && !empty($this->headingFields)) {
            # The field is not numeric and there is heading fields
            $field_key = array_intersect(array_map('strtolower', $this->headingFields), [strtolower($field)]);            
            $sort_by = key($field_key);
        }
        return $sort_by;
    }

    // public function getArgumentsHash() {
    //     # File name is a hash of 
    //     # +(serialized sorting_by)
    //     return md5(serialize($this->sorting_by));
    // }
}