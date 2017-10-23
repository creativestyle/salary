<?php

namespace CS\Utilities\Tests;

use CS\Salary\Calculator\UoDContractCalculator;
use CS\Salary\Configuration\UoDContractConfiguration;

class UoDContractCalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function taxDataProvider()
    {
        return [
            [0.18,    0.50,     1,          1,      1,         0,       false],
            [0.18,    0.50,     2,          2,      1,         0,       false],
            [0.18,    0.50,     3641,       4001,   2001,      360,     false],
            [0.18,    0.50,     889,        977,    489,       88,      false],
            [0.18,    0.20,     286293,     334454, 267563,    48161,   false],
            [0.18,    0.20,     286293,     334455, 267564,    48162,   true],
            [0.19,    0.20,     828,        977,    782,       149,     true],
            [0.19,    0.20,     3395,       4003,   3202,      608,     true],
            [0.19,    0.20,     3395,       4004,   3203,      609,     false],
        ];
    }

    /**
     * For net to gross test we need only the non-ambiguous data part.
     * The problem is that gross->net calculation is a surjection and cannot be
     * reversed in some of this cases.
     *
     * @return array
     */
    public function nonAmibguousTaxDataProvider()
    {
        return array_filter($this->taxDataProvider(), function(array $a) { return !$a[6]; });
    }

    /**
     * @dataProvider nonAmibguousTaxDataProvider
     * @param float $taxRate
     * @param float $incomeCostRate
     * @param int $net
     * @param int $gross
     * @param int $taxBase
     * @param int $tax
     */
    public function testCalculateFromNet(float $taxRate, float $incomeCostRate, int $net, int $gross, int $taxBase, int $tax)
    {
        $config = new UoDContractConfiguration([
            'pit_rate' => $taxRate,
            'income_cost_rate' => $incomeCostRate
        ]);
        
        $calc = new UoDContractCalculator($config);

        $result = $calc->fromNet($net);

        $this->assertEquals($gross, $result->getGross());
        $this->assertEquals($taxBase, $result->getTaxBase());
        $this->assertEquals($tax, $result->getTaxAdvance());
    }

    /**
     * @dataProvider taxDataProvider
     * @param float $taxRate
     * @param float $incomeCostRate
     * @param int $net
     * @param int $gross
     * @param int $taxBase
     * @param int $tax
     */
    public function testCalculateFromGross(float $taxRate, float $incomeCostRate, int $net, int $gross, int $taxBase, int $tax)
    {
        $config = new UoDContractConfiguration([
            'pit_rate' => $taxRate,
            'income_cost_rate' => $incomeCostRate
        ]);

        $calc = new UoDContractCalculator($config);

        $result = $calc->fromGross($gross);

        $this->assertEquals($net, $result->getNet());
        $this->assertEquals($taxBase, $result->getTaxBase());
        $this->assertEquals($tax, $result->getTaxAdvance());
    }

    /**
     * @dataProvider taxDataProvider
     * @param float $PITRate
     * @param float $incomeCostRate
     * @param int $net
     * @param int $gross
     * @param int $taxBase
     * @param int $tax
     */
    public function testReverseCalculationFromNet(float $PITRate, float $incomeCostRate, int $net, int $gross, int $taxBase, int $tax)
    {
        $config = new UoDContractConfiguration([
            'pit_rate' => $PITRate,
            'income_cost_rate' => $incomeCostRate
        ]);

        $calc = new UoDContractCalculator($config);
        $resultA = $calc->fromNet($net);
        $resultB = $calc->fromGross($resultA->getGross());

        $this->assertEquals($resultA->getNet(), $resultB->getNet());
        $this->assertEquals($resultA->getGross(), $resultB->getGross());
        $this->assertEquals($resultA->getTaxBase(), $resultB->getTaxBase());
    }

    /**
     * @dataProvider nonAmibguousTaxDataProvider
     * @param float $PITRate
     * @param float $incomeCostRate
     * @param int $net
     * @param int $gross
     * @param int $taxBase
     * @param int $tax
     */
    public function testReverseCalculationFromGross(float $PITRate, float $incomeCostRate, int $net, int $gross, int $taxBase, int $tax)
    {
        $config = new UoDContractConfiguration([
            'pit_rate' => $PITRate,
            'income_cost_rate' => $incomeCostRate
        ]);

        $calc = new UoDContractCalculator($config);
        $resultA = $calc->fromGross($gross);
        $resultB = $calc->fromNet($resultA->getNet());

        $this->assertEquals($resultA->getNet(), $resultB->getNet());
        $this->assertEquals($resultA->getGross(), $resultB->getGross());
        $this->assertEquals($resultA->getTaxBase(), $resultB->getTaxBase());
    }
}