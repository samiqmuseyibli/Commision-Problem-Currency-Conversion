<?php

namespace Service;

use Model\Amount;
use Model\Operation;
use Model\Transaction;
use Service\Commissions\Commission;
use Service\Commissions\EuCommission;
use Service\Commissions\NotEuCommission;

class CommissionCalculator
{
    private $eu;
    private $no_eu;

    public function __construct(Currencies $converter)
    {
        $this->eu = new EuCommission($converter);
        $this->no_eu = new NotEuCommission($converter);
    }

    public function calculateCommission(Transaction $transaction): Amount
    {
        return $this
            ->pickStrategy($transaction)
            ->calculate($transaction);
    }

    protected function pickStrategy(Transaction $transaction): Commission
    {
        if ($transaction->getOperation()->getType() === Operation::EU) {
            return $this->eu;
        } elseif ($transaction->getOperation()->getType() === Operation::NOT_EU) {
            return $this->no_eu;
        } else {
            throw new \DomainException('Unexpected transaction type');
        }
    }
}