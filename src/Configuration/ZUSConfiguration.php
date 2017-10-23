<?php

namespace CS\Salary\Configuration;


use CS\Salary\AbstractDataBag;
use CS\Salary\DataResolver;

class ZUSConfiguration extends AbstractDataBag
{
    /**
     * {@inheritdoc}
     */
    protected function configure(DataResolver $optionsResolver)
    {
        $optionsResolver->setRequiredWithTypes([
            'employer_emerytalna_rate' => 'double',
            'employer_rentowa_rate' => 'double',
            'employer_wypadkowa_rate' => 'double',
            'employer_fp_rate' => 'double',
            'employer_fgsp_rate' => 'double',
            'employee_emerytalna_rate' => 'double',
            'employee_rentowa_rate' => 'double',
            'employee_chorobowa_rate' => 'double',
            'health_insurance_rate' => 'double',
            'health_insurance_deduction_rate' => 'double',
        ]);
    }

    /**
     * Wypadkowa is always paid even if "no ZUS".
     *
     * @return ZUSConfiguration
     */
    public function withOnlyWypadkowa()
    {
        return new ZUSConfiguration([
            'employer_emerytalna_rate' => 0.0,
            'employer_rentowa_rate' => 0.0,
            'employer_wypadkowa_rate' => $this->get('employer_wypadkowa_rate'),
            'employer_fp_rate' => 0.0,
            'employer_fgsp_rate' => 0.0,
            'employee_emerytalna_rate' => 0.0,
            'employee_rentowa_rate' => 0.0,
            'employee_chorobowa_rate' => 0.0,
            'health_insurance_rate' => 0.0,
            'health_insurance_deduction_rate' => 0.0,
        ]);
    }

    /**
     * @return float
     */
    public function getEmployeeContributionRate(): float
    {
        return $this->get('employee_emerytalna_rate')
            + $this->get('employee_rentowa_rate')
            + $this->get('employee_chorobowa_rate');
    }

    /**
     * @return float
     */
    public function getEmployerContributionRate(): float
    {
        return $this->get('employer_emerytalna_rate')
            + $this->get('employer_rentowa_rate')
            + $this->get('employer_wypadkowa_rate')
            + $this->get('employer_fp_rate')
            + $this->get('employer_fgsp_rate');
    }


    /**
     * Chorobowa is always optional.
     *
     * @return ZUSConfiguration
     */
    public function withoutChorobowa()
    {
        return new ZUSConfiguration(array_merge($this->toArray(), [
            'employee_chorobowa_rate' => 0.0
        ]));
    }

    /**
     * @return float
     */
    public function getEmployerEmerytalnaRate(): float
    {
        return $this->get('employer_emerytalna_rate');
    }

    /**
     * @return float
     */
    public function getEmployerRentowaRate(): float
    {
        return $this->get('employer_rentowa_rate');
    }

    /**
     * @return float
     */
    public function getEmployerWypadkowaRate(): float
    {
        return $this->get('employer_wypadkowa_rate');
    }

    /**
     * @return float
     */
    public function getEmployerFPRate(): float
    {

        return $this->get('employer_fp_rate');
    }

    /**
     * @return float
     */
    public function getEmployerFGSPRate(): float
    {
        return $this->get('employer_fgsp_rate');
    }

    /**
     * @return float
     */
    public function getEmployeeEmerytalnaRate(): float
    {
        return $this->get('employee_emerytalna_rate');
    }

    /**
     * @return float
     */
    public function getEmployeeRentowaRate(): float
    {
        return $this->get('employee_rentowa_rate');
    }

    /**
     * @return float
     */
    public function getEmployeeChorobowaRate(): float
    {
        return $this->get('employee_chorobowa_rate');
    }

    /**
     * @return float
     */
    public function getHealthInsuranceRate(): float
    {
        return $this->get('health_insurance_rate');
    }

    /**
     * @return float
     */
    public function getHealthInsuranceDeductionRate(): float
    {
        return $this->get('health_insurance_deduction_rate');
    }
}