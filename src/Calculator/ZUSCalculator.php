<?php

namespace CS\Salary\Calculator;


use CS\Salary\Calculation\ZUSCalculation;
use CS\Salary\Configuration\ZUSConfiguration;

class ZUSCalculator
{
    use CalculatorHelperTrait;

    /**
     * @var ZUSConfiguration
     */
    private $configuration;

    /**
     * @param ZUSConfiguration $configuration
     */
    public function __construct(ZUSConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param float $gross
     * @return ZUSCalculation
     */
    public function fromGross(float $gross)
    {
        $result = [
            'employer_emerytalna' => $this->roundToCents($gross * $this->configuration->getEmployerEmerytalnaRate()),
            'employer_rentowa' => $this->roundToCents($gross * $this->configuration->getEmployerRentowaRate()),
            'employer_wypadkowa' => $this->roundToCents($gross * $this->configuration->getEmployerWypadkowaRate()),
            'employer_fp' => $this->roundToCents($gross * $this->configuration->getEmployerFPRate()),
            'employer_fgsp' => $this->roundToCents($gross * $this->configuration->getEmployerFGSPRate()),
            'employee_emerytalna' => $this->roundToCents($gross * $this->configuration->getEmployeeEmerytalnaRate()),
            'employee_rentowa' => $this->roundToCents($gross * $this->configuration->getEmployeeRentowaRate()),
            'employee_chorobowa' => $this->roundToCents($gross * $this->configuration->getEmployeeChorobowaRate()),
        ];

        $result['employer_contribution'] = $this->roundToCents(
            $result['employer_emerytalna'] +
            $result['employer_rentowa'] +
            $result['employer_wypadkowa'] +
            $result['employer_fp'] +
            $result['employer_fgsp']
        );

        $result['employee_contribution'] = $this->roundToCents(
            $result['employee_emerytalna'] +
            $result['employee_rentowa'] +
            $result['employee_chorobowa']
        );

        $healthInsuranceBase = $this->roundToCents($gross - $result['employee_contribution']);

        $result['health_insurance'] = $this->roundToCents($healthInsuranceBase * $this->configuration->getHealthInsuranceRate());
        $result['health_insurance_deduction'] = $this->roundToCents($healthInsuranceBase * $this->configuration->getHealthInsuranceDeductionRate());
        $result['health_insurance_base'] = $healthInsuranceBase;

        return new ZUSCalculation($result);
    }

    /**
     * @return ZUSConfiguration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}