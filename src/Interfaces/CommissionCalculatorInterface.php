<?php

namespace App\Interfaces;

interface CommissionCalculatorInterface extends DataTransformerInterface {

    public function getCommissionRate(float $amount, float $exchangeRate, string $countryCode): float;
}
