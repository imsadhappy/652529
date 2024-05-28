<?php

namespace App\Interfaces;

interface ExchangeRateProviderInterface {

    public function getRate(string $from, string $to): float;
}
