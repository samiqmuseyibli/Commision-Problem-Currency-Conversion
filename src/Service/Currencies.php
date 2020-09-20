<?php

namespace Service;

class Currencies
{
    private $data;
    const COMPUTATIONS_SCALE = 10;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getConversionRate(string $from, string $to, int $precision)
    {
        if (!isset($this->data['rates'][$from]) && $this->data['base'] != $from) {
            throw new \RuntimeException("Unknown currency $from");
        }
        if (!isset($this->data['rates'][$to]) && $this->data['base'] != $to) {
            throw new \RuntimeException("Unknown currency $to");
        }

        if ($from !== "EUR") {
            $from = $this->data['rates'][$from];
        } else {
            $from = 1;
        }
        if ($to !== "EUR") {
            $to = $this->data['rates'][$to];
        } else {
            $to = 1;
        }
        //return (string) $from;
        return bcdiv(
            '1',
            bcdiv($to, 1, self::COMPUTATIONS_SCALE),
            self::COMPUTATIONS_SCALE
        );
    }
}