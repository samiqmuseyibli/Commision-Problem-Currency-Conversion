<?php

declare (strict_types = 1);

namespace Model;

use DateTimeImmutable;

class Transaction
{
    private $bin;
    private $currency;
    private $amount;
    private $operation;

    public function __construct(
        $bin,
        $currency,
        Amount $amount,
        Operation $operation
    ) {
        $this->bin = $bin;
        $this->currency = $currency;
        $this->amount = $amount;
        $this->operation = $operation;
    }

    public function getBin(): DateTimeImmutable
    {
        return $this->bin;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getOperation(): Operation
    {
        return $this->operation;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }
}