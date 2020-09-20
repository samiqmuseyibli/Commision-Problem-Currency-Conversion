<?php

namespace Transform;

use Model\Amount;
use Model\Operation;
use Model\Transaction;
use Service\Currencies;

class LineToTransaction
{
    private $currencies;
    private $apiUrl = "https://lookup.binlist.net/";

    public function __construct(Currencies $currencies)
    {
        $this->currencies = $currencies;
    }

    public function __invoke($args)
    {
        $args = json_decode($args, true);
        $bin = $this->buildBin($args['bin']);
        $currency = $this->buildCurrency($args['currency']);
        $amount = $this->buildAmount($args['amount'], $currency);
        $operation = $this->buildOperation($bin);
        return new Transaction($bin, $currency, $amount, $operation);
    }

    private function buildBin(string $value)
    {
        $bin = filter_var($value, FILTER_VALIDATE_INT);
        if (false === $value) {
            throw new \DomainException("Value $value is not a valid amount of money");
        }
        return $bin;
    }

    private function buildCurrency(string $currency)
    {
        return $currency;
    }

    private function buildAmount(string $raw_amount, string $raw_currency): Amount
    {
        $amount = filter_var($raw_amount, FILTER_VALIDATE_FLOAT);
        if (false === $amount) {
            throw new \DomainException("Value $raw_amount is not a valid amount of money");
        }
        // maybe check currency someday

        return new Amount($amount, $raw_currency, $this->currencies);
    }

    public function buildOperation($bin): Operation
    {
        $cnts = [
            "AT", "BE", "BG", "CY", "CZ", "DE", "DK", "EE", "ES", "FI", "FR", "GR", "HR", "HU",
            "IE", "IT", "LT", "LU", "LV", "MT", "NL", "PO", "PT", "RO", "SE", "SI", "SK",
        ];
        try {
            $resp = json_decode(file_get_contents($this->apiUrl . $bin), true);
            //return $resp['country']['alpha2'];
            if (in_array($resp['country']['alpha2'], $cnts)) {
                $operation_type = Operation::EU;
            } else {
                $operation_type = Operation::NOT_EU;
            }
        } catch (\Exception $ex) {
            throw new \RuntimeException('Failed to load Country data');
        }

        return new Operation($operation_type);
    }
}