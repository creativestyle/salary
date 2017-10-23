<?php

namespace CS\Salary\Calculation;


interface ContractCalculationInterface
{
    public function getGross(): float;

    public function getNet(): float;

    public function getEmployerCost(): int;

    public function getTax(): int;

    public function getTaxAdvance(): int;

    public function getTaxBase(): int;

    public function getIncomeCost(): float;

    public function toArray(): array;
}