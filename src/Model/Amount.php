<?php

declare (strict_types = 1);

namespace Model;

use Service\Currencies;

class Amount
{
    const COMPUTATIONS_SCALE = 2;

    private $amount;
    private $currency;
    private $converter;

    public function __construct(string $amount, string $currency, Currencies $converter)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->converter = $converter;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function multiply(string $factor): Amount
    {
        return new Amount(
            bcmul($this->amount, $factor, self::COMPUTATIONS_SCALE),
            $this->currency,
            $this->converter
        );
    }

    public function convert(string $currency): Amount
    {
        return new Amount(
            bcmul(
                $this->amount,
                $this->converter->getConversionRate($this->currency, $currency, self::COMPUTATIONS_SCALE),
                self::COMPUTATIONS_SCALE
            ),
            $currency,
            $this->converter
        );
    }

    public function roundUp()
    {
        //return new Amount();
    }
}