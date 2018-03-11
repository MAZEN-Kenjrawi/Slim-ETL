<?php

namespace ETL\Support\Contracts;

interface TransformerInterface
{
    public function sort_by();

    public function getSortingBy();

    public function getSortedData();

    // public function getArgumentsHash();
}
