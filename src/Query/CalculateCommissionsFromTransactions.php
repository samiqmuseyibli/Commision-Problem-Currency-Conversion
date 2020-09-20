<?php

declare (strict_types = 1);

namespace Query;

use Formigone\Chain;
use Model\Amount;
use Model\Transaction;
use Service\CommissionCalculator;
use Service\Currencies;
use Transform\LineToTransaction;
use Transform\TransactionToCommission;

class CalculateCommissionsFromTransactions implements Query
{
    private $file;
    private $currencies;
    private $apiUrl = "https://api.exchangeratesapi.io/latest";

    public function __construct(string $file)
    {
        $this->file = $file;
        $this->getCurrencies();
    }

    /**
     * @return Transaction[]
     */
    public function execute(): array
    {
        $transactions = Chain::from($this->readLines())
            ->map(new LineToTransaction($this->currencies))
        // todo can make sure transaction log is ordered here
            ->map(new TransactionToCommission(
                new CommissionCalculator($this->currencies)
            ))
            ->map(function (Amount $commission) {
                return $commission->getAmount();
            })
            ->get();

        return $transactions;
    }

    private function readLines(): array
    {
        if (!($file = fopen($this->file, 'r'))) {
            throw new \RuntimeException('Failed to open file');
        }

        $lines = explode("\n", file_get_contents($this->file));

        return $lines;
    }

    private function getCurrencies()
    {
        try {
            $this->currencies = new Currencies(json_decode(file_get_contents($this->apiUrl), true));
        } catch (\Exception $ex) {
            throw new \RuntimeException('Failed to load Currency data');
        }
    }
}