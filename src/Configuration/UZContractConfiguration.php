<?php

namespace CS\Salary\Configuration;


use CS\Salary\AbstractDataBag;
use CS\Salary\DataResolver;

class UZContractConfiguration extends AbstractDataBag
{
    /**
     * {@inheritdoc}
     */
    protected function configure(DataResolver $optionsResolver)
    {
        $optionsResolver->setRequiredWithTypes([
            'income_cost_rate' => 'double',
            'pit_rate' => 'double'
        ]);
    }

    /**
     * @return float
     */
    public function getIncomeCostRate(): float
    {
        return $this->get('income_cost_rate');
    }

    /**
     * @return float
     */
    public function getPitRate(): float
    {
        return $this->get('pit_rate');
    }
}