<?php

use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    /**
     * @var \Service\Currencies
     */
    private $converter;

    public function setUp()
    {
        $currencies = json_decode(file_get_contents("https://api.exchangeratesapi.io/latest"), true);
        $this->converter = new \Service\Currencies($currencies);
    }

    public function testSingleConversion()
    {
        $amount = new \Model\Amount('100', 'EUR', $this->converter);
        $this->assertEquals('1', round($amount->convert('EUR')->multiply('0.01')->getAmount(), 2));
    }

    public function testSingleConversion2()
    {
        $amount = new \Model\Amount('50', 'EUR', $this->converter);
        $this->assertEquals('0.42', round($amount->convert('USD')->multiply('0.01')->getAmount(), 2));
    }

    public function testUnknownCurrency()
    {
        $amount = new \Model\Amount('3', 'EUR', $this->converter);
        $this->expectException(\RuntimeException::class);
        $amount->convert('LTL')->getAmount();
    }
}