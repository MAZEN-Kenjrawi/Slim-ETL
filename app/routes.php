<?php

$app->get('/', ['ETL\Controllers\ETLController', 'default']);

$app->get('/{format}/{filters}/{sort}', ['ETL\Controllers\ETLController', 'index']);

$app->get('/format/{format}', ['ETL\Controllers\ETLController', 'by_format']);

// $app->get('/{format}[/filters/{filters}[/sort/{sort}]]', ['ETL\Controllers\ETLController', 'by_filters']);

// $app->get('/[format/{format}[/filters/{filters}[/sort/{sort}]]]', ['ETL\Controllers\ETLController', 'by_filters']);
// $app->get('/[format/{format}[/sort/{sort}[/filters/{filters}]]]', ['ETL\Controllers\ETLController', 'by_sort']);

// $app->get('/filters/{filters}[/format/{format}[/sort/{sort}]]', ['ETL\Controllers\ETLController', 'filtering']);

// $app->get('/sort/{sort}[/format/{format}[/filters/{filters}]]', ['ETL\Controllers\ETLController', 'sorting']);
