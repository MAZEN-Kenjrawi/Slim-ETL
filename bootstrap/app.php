<?php

use ETL\App;

require_once __DIR__.'/functions.php';
require_once __DIR__.'/../vendor/autoload.php';

$app = new App();

require_once __DIR__.'/../app/routes.php';
