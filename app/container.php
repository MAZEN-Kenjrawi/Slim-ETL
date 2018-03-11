<?php

use ETL\Models\ETL;
use ETL\Models\ExtractorCSV;
use ETL\Models\Loader;
use ETL\Models\Transformer;
use ETL\Support\Contracts\ExtractorInterface;
use ETL\Support\Contracts\LoaderInterface;
use ETL\Support\Contracts\TransformerInterface;
use Interop\Container\ContainerInterface;

return [
    ExtractorInterface::class => function (ContainerInterface $c) {
        return new ExtractorCSV();
    },

    TransformerInterface::class => function (ContainerInterface $c) {
        return new Transformer();
    },

    LoaderInterface::class => function (ContainerInterface $c) {
        return new Loader();
    },

    ETL::class => function (ContainerInterface $c) {
        return new ETL(
            $c->get(ExtractorCSV::class),
            $c->get(Transformer::class),
            $c->get(Loader::class)
        );
    },
];
