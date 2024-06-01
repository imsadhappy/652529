<?php declare(strict_types=1);

namespace App\DataTransformers;

use App\Enums\EUCountries;
use App\DataTransformers\BaseCommissionCalculator;
use App\Interfaces\CommissionCalculatorInterface;
use Brick\Money\Money;

class EUCommissionCalculator extends BaseCommissionCalculator implements CommissionCalculatorInterface {

    public function getCommissionRate(Money $amount, string $countryCode): float
    {
        //this method would probably retrieve rates from DB
        return defined(EUCountries::class.'::'.$countryCode) ? 0.01 : 0.02;
    }
}
