<?php

if (!isset($argv[1])) {
    exit("run 'php app.php filename.ext' to calculate transaction commissions");
}

require_once __DIR__.'/vendor/autoload.php';

$_ENV = array_merge($_ENV, [
    'READ_FROM' => $argv[1],
    'BASE_CURRENCY' => 'EUR',
    'COMMAND' => 'App\Commands\CalculateCommissionsCommand',
    'COMMISION_READER' => 'App\Utils\FileReader',
    'COMMISION_PARSER' => 'App\Utils\JSONParser',
    'COMMISION_CALCULATOR' => 'App\DataTransformers\EUCommissionCalculator',
    'EXCHANGE_RATE_CACHE_EXPIRATION' => strtotime('+12 hours') - time(),
    'BIN_CONVERTER_CACHE_EXPIRATION' => strtotime('+1 year') - time()
], parse_ini_file(__DIR__.'/.env'));

require_once __DIR__.'/src/run-command.php';
