PL Salary Calc Libraries
========================

Library for calculating compensation for **Umowa o Dzieło** and **Umowa Zlecenie**.

What is the most interesting feature is that reverse calculation from net value or employer cost is possible.

The interface is pretty simple, look at the tests if unsure.

## Installation

```
composer require creativestyle/salary
```

## Basic usage

```php
// Create configuration for your calculator - look at the configuration's
// `::configure` method to know what values to provide
$conf = new UoDContractConfiguration([
    'pit_rate' => 0.18,
    'income_cost_rate' => 0.2,
]);

// Create the desired calculator
$calc = new UoDContractCalculator($conf);

// Calculate!
$result = $calc->fromNet(2000);

// Universal values are provided by `ContractCalculationInterface` but you can get any contract-specific value
// Look at `AbstractDataBag` and appropriate Calculation class to get the picture
$gross = $result->getGross();

// Query the underlying DataBag
$incomeCost = $result->get('income_cost');
```

## TODO

A factory for constructing calculators/configs based on the date. We would need historic values for the taxes
and implementation of new calculators if the tax rules change.

## Caveat Emptor!

Be careful here because gross->net calculation is a surjection (because of rounding at the intermediate steps) and 
cannot be always reversed to the same value. You should store both values after initial user's input and then reuse 
them instead of recalculating (or correct the net, by recalculation it from resulting gross).


