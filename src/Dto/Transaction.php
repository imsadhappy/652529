<?php declare(strict_types=1);

namespace App\Dto;

use Brick\Money\Money;

class Transaction
{
    public int $bin;
    public Money $amount;
    public string $currency;
}
