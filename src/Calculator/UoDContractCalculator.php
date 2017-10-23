<?php

namespace CS\Salary\Calculator;

use CS\Salary\Calculation\ContractCalculationInterface;
use CS\Salary\Calculation\UoDContractCalculation;
use CS\Salary\AbstractDataBag;
use CS\Salary\Configuration\UoDContractConfiguration;

final class UoDContractCalculator implements ContractCalculatorInterface
{
    use CalculatorHelperTrait;

    /**
     * @var UoDContractConfiguration
     */
    private $configuration;

    /**
     * @param UoDContractConfiguration $configuration
     */
    public function __construct(UoDContractConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return AbstractDataBag
     */
    public function getConfiguration(): AbstractDataBag
    {
        return $this->configuration;
    }

    /**
     * @param float $gross
     * @return float
     */
    private function calculateIncomeCost(float $gross)
    {
        return $this->roundToCents($gross * $this->configuration->getIncomeCostRate());
    }

    /**
     * @param float $gross
     * @return int
     */
    private function calculateTaxBase(float $gross)
    {
        return $this->roundToCardinal($gross - $this->calculateIncomeCost($gross));
    }

    /**
     * @param float $gross
     * @return int
     */
    private function calculatePIT(float $gross)
    {
        return $this->roundToCardinal($this->calculateTaxBase($gross) * $this->configuration->getPITRate());
    }

    /**
     * @param float $gross
     * @return int
     */
    private function calculateNet(float $gross)
    {
        return $this->roundToCardinal($gross - $this->calculatePIT($gross));
    }

    /**
     * @param float $net
     * @return int
     */
    private function calculateGross(float $net)
    {
        return $this->roundToCardinal(
            $net /
            (
                1.0 -
                $this->configuration->getPITRate() +
                ($this->configuration->getPITRate() * $this->configuration->getIncomeCostRate())
            )
        );
    }

    /**
     * Be careful here because gross->net calculation is a surjection and cannot be always reversed
     * to the same value. You should store both values after initial user's input and then reuse them
     * instead of recalculating.
     *
     * @param float $net
     * @return ContractCalculationInterface
     */
    public function fromNet(float $net): ContractCalculationInterface
    {
        return $this->fromGross(
            $this->calculateGross($net)
        );
    }

    /**
     * @param float $gross
     * @return ContractCalculationInterface
     */
    public function fromGross(float $gross): ContractCalculationInterface
    {
        return new UoDContractCalculation([
            'gross' => $gross,
            'net' => $this->calculateNet($gross),
            'tax' => $this->calculatePIT($gross),
            'tax_base' => $this->calculateTaxBase($gross),
            'income_cost' => $this->calculateIncomeCost($gross)
        ]);
    }

    /**
     * @param float $companyCost
     * @return ContractCalculationInterface
     */
    public function fromEmployerCost(float $companyCost): ContractCalculationInterface
    {
        return $this->fromGross($companyCost);
    }
}