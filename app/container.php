<?php

use Interop\Container\ContainerInterface;

use ETL\Support\Contracts\ETLAbstract;
use ETL\Models\ETL;

use ETL\Support\Contracts\ExtractorInterface;
use ETL\Models\ExtractorCSV;

use ETL\Support\Contracts\TransformerInterface;
use ETL\Models\Transformer;

use ETL\Support\Contracts\LoaderInterface;
use ETL\Models\Loader;

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
