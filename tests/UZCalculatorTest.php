<?php

namespace CS\Utilities\Tests;

use CS\Salary\Calculator\UZContractCalculator;
use CS\Salary\Configuration\UZContractConfiguration;
use CS\Salary\Configuration\ZUSConfiguration;

class UZContractCalculatorTest extends \PHPUnit_Framework_TestCase
{
    private function getZUSConfig(bool $withZus, bool $withChorobowa)
    {
        $zusConfig = new ZUSConfiguration([
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
        ]);

        if (!$withZus) {
            $zusConfig = $zusConfig->withOnlyWypadkowa();
        }

        if (!$withChorobowa) {
            $zusConfig = $zusConfig->withoutChorobowa();
        }

        return $zusConfig;
    }

    /**
     * @return array
     */
    public function taxDataProvider()
    {
        return [
            [true, false, [
                'income_cost_rate' => 0.2,
                'pit_rate' => 0.18
            ], [
                'gross' => 4001.0,
                'net' => 2994.94,
                'tax' => 236,
                'tax_base' => 2840,
                'income_cost' => 710.10,
                'employer_cost' => 4825.61,
            ]],
            [true, true, [
                'income_cost_rate' => 0.5,
                'pit_rate' => 0.18
            ], [
                'gross' => 10321.0,
                'net' => 7993.45,
                'tax' => 111,
                'tax_base' => 4453,
                'income_cost' => 4453.0,
                'employer_cost' => 12448.16,
            ]],
            [false, false, [
                'income_cost_rate' => 0.5,
                'pit_rate' => 0.18
            ], [
                'gross' => 2016.0,
                'net' => 1835.0,
                'tax' => 181,
                'tax_base' => 1008.0,
                'income_cost' => 1008.0,
                'employer_cost' => 2052.29,
            ]],
        ];
    }

    /**
     * @dataProvider taxDataProvider
     * @param bool $withZus
     * @param bool $withChorobowa
     * @param array $configData
     * @param array $result
     * @internal param array $result
     */
    public function testCalculateFromGross(bool $withZus, bool $withChorobowa, array $configData, array $result)
    {
        $zusConfig = $this->getZUSConfig($withZus, $withChorobowa);

        $calculator = new UZContractCalculator(
            new UZContractConfiguration($configData),
            $zusConfig
        );

        $calculation = $calculator->fromGross($result['gross']);
        $calculationData = $calculation->toArray();

        unset($calculationData['zus_calculation']);

        $this->assertEquals(
            $result,
            $calculationData
        );
    }

    /**
     * @dataProvider taxDataProvider
     * @param bool $withZus
     * @param bool $withChorobowa
     * @param array $configData
     * @param array $result
     * @internal param array $result
     */
    public function testCalculateFromNet(bool $withZus, bool $withChorobowa, array $configData, array $result)
    {
        $zusConfig = $this->getZUSConfig($withZus, $withChorobowa);

        $calculator = new UZContractCalculator(
            new UZContractConfiguration($configData),
            $zusConfig
        );

        $calculation = $calculator->fromNet($result['net']);
        $calculationData = $calculation->toArray();

        unset($calculationData['zus_calculation']);

        $this->assertEquals(
            $result,
            $calculationData
        );
    }

    /**
     * @dataProvider taxDataProvider
     * @param bool $withZus
     * @param bool $withChorobowa
     * @param array $configData
     * @param array $result
     * @internal param array $result
     */
    public function testCalculateFromEmployerCost(bool $withZus, bool $withChorobowa, array $configData, array $result)
    {
        $zusConfig = $this->getZUSConfig($withZus, $withChorobowa);

        $calculator = new UZContractCalculator(
            new UZContractConfiguration($configData),
            $zusConfig
        );

        $calculation = $calculator->fromEmployerCost($result['employer_cost']);
        $calculationData = $calculation->toArray();

        unset($calculationData['zus_calculation']);

        $this->assertEquals(
            $result,
            $calculationData
        );
    }

    /**
     * @dataProvider taxDataProvider
     * @param bool $withZus
     * @param bool $withChorobowa
     * @param array $configData
     * @param array $result
     */
    public function testReverseCalculationFromNet(bool $withZus, bool $withChorobowa, array $configData, array $result)
    {
        $zusConfig = $this->getZUSConfig($withZus, $withChorobowa);

        $calculator = new UZContractCalculator(
            new UZContractConfiguration($configData),
            $zusConfig
        );

        $resultA = $calculator->fromNet($result['net']);
        $resultB = $calculator->fromGross($resultA->getGross());

        $this->assertEquals($resultA->getNet(), $resultB->getNet());
        $this->assertEquals($resultA->getGross(), $resultB->getGross());
    }

    /**
     * @dataProvider taxDataProvider
     * @param bool $withZus
     * @param bool $withChorobowa
     * @param array $configData
     * @param array $result
     */
    public function testReverseCalculationFromGross(bool $withZus, bool $withChorobowa, array $configData, array $result)
    {
        $zusConfig = $this->getZUSConfig($withZus, $withChorobowa);

        $calculator = new UZContractCalculator(
            new UZContractConfiguration($configData),
            $zusConfig
        );

        $resultA = $calculator->fromGross($result['gross']);
        $resultB = $calculator->fromNet($resultA->getNet());

        $this->assertEquals($resultA->getNet(), $resultB->getNet());
        $this->assertEquals($resultA->getGross(), $resultB->getGross());
    }

    /**
     * @dataProvider taxDataProvider
     * @param bool $withZus
     * @param bool $withChorobowa
     * @param array $configData
     * @param array $result
     */
    public function testReverseCalculationFromEmployerCost(bool $withZus, bool $withChorobowa, array $configData, array $result)
    {
        $zusConfig = $this->getZUSConfig($withZus, $withChorobowa);

        $calculator = new UZContractCalculator(
            new UZContractConfiguration($configData),
            $zusConfig
        );

        $resultA = $calculator->fromEmployerCost($result['employer_cost']);
        $resultB = $calculator->fromNet($resultA->getNet());

        $this->assertEquals($resultA->getNet(), $resultB->getNet());
        $this->assertEquals($resultA->getEmployerCost(), $resultB->getEmployerCost());
    }
}