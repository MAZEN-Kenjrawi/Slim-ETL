<?php
// Starting Time
// $start = microtime(true);
// define('IS_AJAX', (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));

// Require The App.php File
require_once __DIR__.'/../bootstrap/app.php';

$app->run();

// End Time
// $end = microtime(true);

// if (!IS_AJAX) {
    // Output in comment tag, the total excuted time
    // echo "\n<!-- ".($end - $start).' -->';
// }