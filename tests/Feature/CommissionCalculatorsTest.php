<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\DataTransformers\BaseCommissionCalculator;
use App\DataTransformers\EUCommissionCalculator;
use Brick\Money\Money;

class CommissionCalculatorsTest extends TestCase
{

    public function testAbortForNegativeNumbers(): void
    {
        $this->expectException(\RangeException::class);
        $commissionCalculator = new BaseCommissionCalculator();
        $commissionCalculator(Money::of(-1, 'USD'));
    }

    public function testCalculateBaseCommission(): void
    {
        $commissionCalculator = new BaseCommissionCalculator();
        $this->assertSame('0.00', (string) $commissionCalculator(Money::of(1, 'USD'))->getAmount());
    }

    public function testCalculateEuCommissions(): void
    {
        $commissionCalculator = new EUCommissionCalculator();
        $this->assertSame('1.00', (string) $commissionCalculator(Money::of(100, 'EUR'), 'LV')->getAmount());
        $this->assertSame('2.00', (string) $commissionCalculator(Money::of(100, 'EUR'), 'US')->getAmount());
    }
}
