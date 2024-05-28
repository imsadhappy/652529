<?php

use App\Commands\CalculateCommissionsCommand;
use App\Services\ExchangerateAPI;
use App\Services\RapidAPIBinChecker;
use App\Utils\FileReader;
use App\Utils\JSONParser;
use App\Enums\EUCountries;

require_once __DIR__ . '/vendor/autoload.php';

if (!isset($argv[1])) {
    exit("run 'php app.php filename.ext' to calculate transaction commissions");
}

new CalculateCommissionsCommand(
    $argv[1],
    $argv[2] ?? 'EUR',
    new FileReader(),
    new JSONParser(),
    new RapidAPIBinChecker('ed925f5de6msh382502fb7cef76dp15db54jsnb2ebda1b8412'),
    new ExchangerateAPI('c7dd82c9adc0c9a57e1804dd'),
    function($ammountInBaseCurrency, $countryCode) {
        $commission = $ammountInBaseCurrency * (defined(EUCountries::class.'::'.$countryCode) ?
                                                0.01 :
                                                0.02);
        echo number_format($commission, 2, '.', '') . PHP_EOL;
    }
);
