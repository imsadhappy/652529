<?php

use App\Commands\CalculateCommissionsCommand;
use App\Utils\FileReader;
use App\Utils\JSONParser;

require_once __DIR__ . '/vendor/autoload.php';

if ( ! isset($argv[1]) ) {
    exit("run 'php app.php filename.ext' to calculate transaction commissions");
}

new CalculateCommissionsCommand(
    $argv[1],
    new FileReader(),
    new JSONParser()
);
