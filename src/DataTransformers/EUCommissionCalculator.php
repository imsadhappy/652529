<?php declare(strict_types=1);

namespace App\DataTransformers;

use App\Enums\EUCountries;
use App\DataTransformers\BaseCommissionCalculator;
use App\Interfaces\CommissionCalculatorInterface;

class EUCommissionCalculator extends BaseCommissionCalculator implements CommissionCalculatorInterface {

    public function getCommissionRate(float $amount, float $exchangeRate, string $countryCode): float
    {
        return defined(EUCountries::class.'::'.$countryCode) ? 0.01 : 0.02;
    }
}
