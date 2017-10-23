<?php

namespace CS\Utilities\Tests;

use CS\Salary\Calculator\ZUSCalculator;
use CS\Salary\Configuration\ZUSConfiguration;

class ZUSContractCalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function taxDataProvider()
    {
        $zusConfig = [
            'employer_emerytalna_rate' => 0.0976,
            'employer_rentowa_rate' => 0.0650,
            'employer_wypadkowa_rate' => 0.0180,
            'employer_fp_rate' => 0.0245,
            'employer_fgsp_rate' => 0.0010,
            'employee_emerytalna_rate' => 0.0976,
            'employee_rentowa_rate' => 0.0150,
            'employee_chorobowa_rate' => 0.0245,
            'health_insurance_rate' => 0.09,
            'health_insurance_deduction_rate' => 0.0775,
        ];

        return [
            [$zusConfig, 4000, [
                'employer_emerytalna' => 390.4,
                'employer_rentowa' => 260.0,
                'employer_wypadkowa' => 72.0,
                'employer_fp' => 98.0,
                'employer_fgsp' => 4.0,
                'employee_emerytalna' => 390.4,
                'employee_rentowa' => 60.0,
                'employee_chorobowa' => 98.0,
                'employer_contribution' => 824.4,
                'employee_contribution' => 548.4,
                'health_insurance_base' => 3451.60,
                'health_insurance' => 310.64,
                'health_insurance_deduction' => 267.50,
            ]],
            [$zusConfig, 14549, [
                'employer_emerytalna' => 1419.98,
                'employer_rentowa' => 945.69,
                'employer_wypadkowa' => 261.88,
                'employer_fp' => 356.45,
                'employer_fgsp' => 14.55,
                'employee_emerytalna' => 1419.98,
                'employee_rentowa' => 218.24,
                'employee_chorobowa' => 356.45,
                'employer_contribution' => 2998.55,
                'employee_contribution' => 1994.67,
                'health_insurance_base' => 12554.33,
                'health_insurance' => 1129.89,
                'health_insurance_deduction' => 972.96,
            ]],
            [$zusConfig, 1151, [
                'employer_emerytalna' => 112.34,
                'employer_rentowa' => 74.82,
                'employer_wypadkowa' => 20.72,
                'employer_fp' => 28.2,
                'employer_fgsp' => 1.15,
                'employee_emerytalna' => 112.34,
                'employee_rentowa' => 17.27,
                'employee_chorobowa' => 28.2,
                'employer_contribution' => 237.23,
                'employee_contribution' => 157.81,
                'health_insurance_base' => 993.19,
                'health_insurance' => 89.39,
                'health_insurance_deduction' => 76.97,
            ]],
        ];
    }

    /**
     * @dataProvider taxDataProvider
     * @param array $zusConfig
     * @param int $gross
     * @param array $result
     */
    public function testCalculate(array $zusConfig, int $gross, array $result)
    {
        $config = new ZUSConfiguration($zusConfig);
        $calculator = new ZUSCalculator($config);

        $calculation = $calculator->fromGross($gross);

        $this->assertEquals($result, $calculation->toArray());
    }
}