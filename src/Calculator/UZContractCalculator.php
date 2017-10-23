<?php

namespace CS\Salary\Calculator;

use CS\Salary\Calculation\ContractCalculationInterface;
use CS\Salary\Calculation\UZContractCalculation;
use CS\Salary\Configuration\UZContractConfiguration;
use CS\Salary\Configuration\ZUSConfiguration;

final class UZContractCalculator implements ContractCalculatorInterface
{
    use CalculatorHelperTrait;

    /**
     * @var UZContractConfiguration
     */
    private $configuration;

    /**
     * @var ZUSCalculator
     */
    private $zusCalculator;

    /**
     * @var ZUSConfiguration
     */
    private $zusConfiguration;

    /**
     * @param UZContractConfiguration $configuration
     * @param ZUSConfiguration $zusConfiguration
     */
    public function __construct(
        UZContractConfiguration $configuration,
        ZUSConfiguration $zusConfiguration
    ) {
        $this->configuration = $configuration;
        $this->zusConfiguration = $zusConfiguration;
        $this->zusCalculator = new ZUSCalculator($zusConfiguration);
    }

    /**
     * {@inheritdoc}
     */
    public function fromNet(float $net): ContractCalculationInterface
    {
        $zusConfiguration = $this->zusCalculator->getConfiguration();

        $zr = $zusConfiguration->getEmployeeContributionRate();
        $ur = $zusConfiguration->getHealthInsuranceRate();
        $or = $zusConfiguration->getHealthInsuranceDeductionRate();
        $pr = $this->configuration->getPitRate();
        $kr = $this->configuration->getIncomeCostRate();

        $gross = $this->roundToCardinal($net / (
            1.0 - $zr - $ur + ($zr * $ur) - $pr + ($zr * $pr) + ($kr * $pr) - ($zr * $kr * $pr) + $or - ($zr * $or)
        ));

        return $this->fromGross($gross);
    }

    /**
     * {@inheritdoc}
     */
    public function fromGross(float $gross): ContractCalculationInterface
    {
        $zusCalculation = $this->zusCalculator->fromGross($gross);

        $incomeCost = $this->roundToCents(
            $this->configuration->getIncomeCostRate() * $zusCalculation->getHealthInsuranceBase()
        );

        $taxBase = $this->roundToCardinal(
            $gross - $incomeCost - $zusCalculation->getEmployeeContribution()
        );

        $pitAdvance = $this->roundToCardinal(
            ($this->configuration->getPitRate() * $taxBase) - $zusCalculation->getHealthInsuranceDeduction()
        );

        $net = $this->roundToCents(
            $gross - $zusCalculation->getEmployeeContribution() - $zusCalculation->getHealthInsurance() - $pitAdvance
        );

        $employersCost = $this->roundToCents($gross + $zusCalculation->getEmployerContribution());

        return new UZContractCalculation([
            'gross' => $gross,
            'net' => $net,
            'tax' => $pitAdvance,
            'tax_base' => $taxBase,
            'income_cost' => $incomeCost,
            'zus_calculation' => $zusCalculation,
            'employer_cost' => $employersCost,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function fromEmployerCost(float $companyCost): ContractCalculationInterface
    {
        return $this->fromGross(
            $this->roundToCardinal($companyCost / (1.0 + $this->zusConfiguration->getEmployerContributionRate()))
        );
    }
}