<?php

namespace CS\Salary\Calculator;

use CS\Salary\Calculation\ContractCalculationInterface;

interface ContractCalculatorInterface
{
    /**
     * @param float $net
     * @return ContractCalculationInterface
     */
    public function fromNet(float $net): ContractCalculationInterface;

    /**
     * @param float $gross
     * @return ContractCalculationInterface
     */
    public function fromGross(float $gross): ContractCalculationInterface;

    /**
     * @param float $companyCost
     * @return ContractCalculationInterface
     */
    public function fromEmployerCost(float $companyCost): ContractCalculationInterface;
}