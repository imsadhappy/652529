<?php declare(strict_types=1);

namespace App\Dto;

class Transaction
{
    public int $bin;
    public float $amount;
    public string $currency;
}
