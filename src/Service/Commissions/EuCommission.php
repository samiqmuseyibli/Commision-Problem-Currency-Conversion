<?php

namespace Service\Commissions;

use Model\Amount;
use Model\Transaction;
use Service\Currencies;

class EuCommission implements Commission
{
    const COMMISSION_RATE = '0.01';
    private $floor;

    public function __construct(Currencies $converter)
    {
        $this->floor = new Amount('1.0', 'EUR', $converter);
    }

    public function calculate(Transaction $transaction): Amount
    {
        return $transaction
            ->getAmount()
            ->multiply(self::COMMISSION_RATE)
            ->convert($transaction->getAmount()->getCurrency());
    }
}