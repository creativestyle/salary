<?php

namespace CS\Salary\Calculation;

use CS\Salary\AbstractDataBag;
use CS\Salary\DataResolver;

class UZContractCalculation extends AbstractDataBag implements ContractCalculationInterface
{
    /**
     * {@inheritdoc}
     */
    protected function configure(DataResolver $optionsResolver)
    {
        $optionsResolver->setRequiredWithTypes([
            'gross' => ['double', 'integer'],
            'net' => ['double', 'integer'],
            'tax' => 'integer',
            'tax_base' => 'integer',
            'income_cost' => ['double', 'integer'],
            'zus_calculation' => ZUSCalculation::class,
            'employer_cost' => ['double', 'integer'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getGross(): float
    {
        return $this->get('gross');
    }

    /**
     * {@inheritdoc}
     */
    public function getNet(): float
    {
        return $this->get('net');
    }

    /**
     * {@inheritdoc}
     */
    public function getTax(): int
    {
        return $this->get('tax');
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxAdvance(): int
    {
        return $this->get('tax');
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxBase(): int
    {
        return $this->get('tax_base');
    }

    /**
     * {@inheritdoc}
     */
    public function getIncomeCost(): float
    {
        return $this->get('income_cost');
    }

    /**
     * {@inheritdoc}
     */
    public function getEmployerCost(): int
    {
        return $this->get('employer_cost');
    }
}