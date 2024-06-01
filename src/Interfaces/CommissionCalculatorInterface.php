<?php

namespace App\Interfaces;

use Brick\Money\Money;

interface CommissionCalculatorInterface {

    public function getCommissionRate(Money $amount, string $countryCode): float;
}
