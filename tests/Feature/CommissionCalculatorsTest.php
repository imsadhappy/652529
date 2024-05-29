<?php

namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use App\DataTransformers\BaseCommissionCalculator;
use App\DataTransformers\EUCommissionCalculator;
use App\Interfaces\CommissionCalculatorInterface;

class CommissionCalculatorsTest extends TestCase
{

    private CommissionCalculatorInterface $commissionCalculator;

    public function testAbortForNegativeNumbers(): void
    {
        $this->expectException(\RangeException::class);
        $commissionCalculator = new BaseCommissionCalculator();
        $commissionCalculator(-0.01);
    }

    public function testCalculateBaseCommission(): void
    {
        $commissionCalculator = new BaseCommissionCalculator();
        $this->assertEquals(0.0, $commissionCalculator(100));
    }

    public function testCalculateEuCommission(): void
    {
        $commissionCalculator = new EUCommissionCalculator();
        $this->assertEquals(1.00, $commissionCalculator(100, 1, 'LV'));
    }

    public function testCalculateNonEuCommission(): void
    {
        $commissionCalculator = new EUCommissionCalculator();
        $this->assertEquals(2.00, $commissionCalculator(100, 1, 'US'));
    }
}
