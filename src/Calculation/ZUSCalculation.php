<?php

namespace CS\Salary\Calculation;

use CS\Salary\AbstractDataBag;
use CS\Salary\DataResolver;

class ZUSCalculation extends AbstractDataBag
{
    /**
     * {@inheritdoc}
     */
    protected function configure(DataResolver $optionsResolver)
    {
        $optionsResolver->setRequiredWithTypes([
            'employer_emerytalna' => 'double',
            'employer_rentowa' => 'double',
            'employer_wypadkowa' => 'double',
            'employer_fp' => 'double',
            'employer_fgsp' => 'double',
            'employee_emerytalna' => 'double',
            'employee_rentowa' => 'double',
            'employee_chorobowa' => 'double',
            'employer_contribution' => 'double',
            'employee_contribution' => 'double',
            'health_insurance_base' => 'double',
            'health_insurance' => 'double',
            'health_insurance_deduction' => 'double',
        ]);
    }

    /**
     * @return float
     */
    public function getEmployerEmerytalna(): float
    {
        return $this->get('employer_emerytalna');
    }

    /**
     * @return float
     */
    public function getEmployerRentowa(): float
    {
        return $this->get('employer_rentowa');
    }

    /**
     * @return float
     */
    public function getEmployerWypadkowa(): float
    {
        return $this->get('employer_wypadkowa');
    }

    /**
     * @return float
     */
    public function getEmployerFP(): float
    {
        return $this->get('employer_fp');
    }

    /**
     * @return float
     */
    public function getEmployerFGSP(): float
    {
        return $this->get('employer_fgsp');
    }

    /**
     * @return float
     */
    public function getEmployeeEmerytalna(): float
    {
        return $this->get('employee_emerytalna');
    }

    /**
     * @return float
     */
    public function getEmployeeRentowa(): float
    {
        return $this->get('employee_rentowa');
    }

    /**
     * @return float
     */
    public function getEmployeeChorobowa(): float
    {
        return $this->get('employee_chorobowa');
    }

    /**
     * @return float
     */
    public function getEmployerContribution(): float
    {
        return $this->get('employer_contribution');
    }

    /**
     * @return float
     */
    public function getEmployeeContribution(): float
    {
        return $this->get('employee_contribution');
    }

    /**
     * @return float
     */
    public function getHealthInsurance(): float
    {
        return $this->get('health_insurance');
    }

    /**
     * @return float
     */
    public function getHealthInsuranceBase(): float
    {
        return $this->get('health_insurance_base');
    }

    /**
     * @return float
     */
    public function getHealthInsuranceDeduction(): float
    {
        return $this->get('health_insurance_deduction');
    }
}