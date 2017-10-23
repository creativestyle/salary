<?php

namespace CS\Salary\Configuration;


use CS\Salary\AbstractDataBag;
use CS\Salary\DataResolver;

class UoDContractConfiguration extends AbstractDataBag
{
    /**
     * {@inheritdoc}
     */
    protected function configure(DataResolver $optionsResolver)
    {
        $optionsResolver->setRequiredWithTypes([
            'pit_rate' => 'double',
            'income_cost_rate' => 'double',
        ]);
    }

    /**
     * @return float
     */
    public function getPITRate(): float
    {
        return $this->get('pit_rate');
    }

    /**
     * @return float
     */
    public function getIncomeCostRate(): float
    {
        return $this->get('income_cost_rate');
    }
}