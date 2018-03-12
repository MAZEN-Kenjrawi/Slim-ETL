<?php

namespace ETL\Support\Contracts;

interface ExtractorInterface
{
    public function getData();

    public function getArgumentsHash();

    public function getHeading();

    public function getCountOfData();
}
