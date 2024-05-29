<?php declare(strict_types=1);

namespace App\DataTransformers;

use App\Interfaces\CommissionCalculatorInterface;

class BaseCommissionCalculator implements CommissionCalculatorInterface {

    public function getCommissionRate(float $amount, float $exchangeRate, string $countryCode): float
    {
        return 0.0;
    }

    function __invoke(float $amount, float $exchangeRate = 1.0, string $countryCode = ''): float
    {
        if ($amount <= 0.0 || $exchangeRate <= 0.0) {
            throw new \RangeException("Positive values required");
        }

        $ammountInBaseCurrency = $amount * $exchangeRate;
        $commission = $ammountInBaseCurrency * $this->getCommissionRate($amount, $exchangeRate, $countryCode);

        return round($commission, 2);
    }
}
