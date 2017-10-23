<?php

namespace CS\Salary\Calculator;

trait CalculatorHelperTrait
{
    /**
     * Performs accounting rounding to cardinal
     *
     * @param float $amount
     * @return int
     */
    protected function roundToCardinal(float $amount)
    {
        return (int)round($amount, 0, PHP_ROUND_HALF_UP);
    }

    /**
     * Performs accounting rounding to cents
     *
     * @param float $amount
     * @return int
     */
    protected function roundToCents(float $amount)
    {
        return round($amount, 2, PHP_ROUND_HALF_UP);
    }
}