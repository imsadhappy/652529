<?php

namespace App\Interfaces;

use Brick\Money\ExchangeRateProvider\ConfigurableProvider;

interface ExchangeRateProviderInterface {

    public function getRate(string $from, string $to): ConfigurableProvider;
}
