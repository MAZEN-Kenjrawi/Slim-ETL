<?php

namespace ETL\Support\Contracts;

use IteratorAggregate;

abstract class ETLAbstract implements IteratorAggregate
{
    protected $data = [];
    protected $heading_fields = [];

    public function getData()
    {
    }

    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }
}
