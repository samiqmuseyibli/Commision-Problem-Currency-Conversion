<?php

require_once 'vendor/autoload.php';

if (!isset($argv[1]) || !is_file($file = $argv[1])) {
    printf("php %s <file>\n", __FILE__);
    exit(1);
}

$currencies = [
    'EUR' => [
        'rate' => 1.,
        'precision' => 2,
    ],
    'USD' => [
        'rate' => 1.1497,
        'precision' => 2,
    ],
    'JPY' => [
        'rate' => 129.53,
        'precision' => 0,
    ],
    'GBP' => [
        'rate' => 0.92,
        'precision' => 0,
    ],
];

$transactionsQuery = new \Query\CalculateCommissionsFromTransactions(
    $file
);

try {
    $transactions = $transactionsQuery->execute();
    print_r($transactions);
    //echo implode("\n", $transactions);
} catch (Exception $ex) {
    echo $ex->getMessage();
    exit(1);
}