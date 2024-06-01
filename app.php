<?php

if (!isset($argv[1])) {
    exit("run 'php app.php filename.ext' to calculate transaction commissions");
}

require_once __DIR__.'/vendor/autoload.php';

$_ENV = array_merge($_ENV, [
    'READ_FROM' => $argv[1],
    'BASE_CURRENCY' => 'EUR',
    'ROUNDING_MODE' => 'UP',
    'COMMAND' => 'App\Commands\CalculateTransactionCommissionCommand',
    'COMMISION_READER' => 'App\Utils\FileReader',
    'COMMISION_PARSER' => 'App\Utils\JSONParser',
    'COMMISION_CALCULATOR' => 'App\DataTransformers\EUCommissionCalculator',
    'BIN_CONVERTER_CACHE_EXPIRATION' => strtotime('+1 hour') - time()
], parse_ini_file(__DIR__.'/.env'));

require_once __DIR__.'/src/run-command.php';
