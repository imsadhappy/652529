<?php declare(strict_types=1);

namespace App\DataTransformers;

use App\Interfaces\CommissionCalculatorInterface;

use Brick\Money\Money;
use Brick\Math\RoundingMode;

class BaseCommissionCalculator implements CommissionCalculatorInterface {

    public function getCommissionRate(Money $amount, string $countryCode): float
    {
        return 0.0;
    }

    function __invoke(Money $amount, string $countryCode = '', RoundingMode $roundingMode = RoundingMode::UP): Money
    {

        if ($amount->getAmount()->isNegativeOrZero()) {
            throw new \RangeException("Can't apply commission to negative amount");
        }

        $commisionRate = $this->getCommissionRate($amount, $countryCode);

        return $amount->multipliedBy($commisionRate, $roundingMode);
    }
}
