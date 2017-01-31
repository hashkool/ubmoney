<?php

/**
 * This file is part of the Ulabox Money library.
 *
 * Copyright (c) 2011-2015 Ulabox SL
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Celtric\UbMoney;

/**
 * @coversDefaultClass Celtric\UbMoney\Currency
 * @uses Celtric\UbMoney\Currency
 * @uses Celtric\UbMoney\Money
 */
final class CurrencyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        $currency = Currency::fromIsoCode('EUR');

        $this->assertEquals('EUR', $currency->getIsoCode());
    }

    /**
     * @covers ::code
     * @covers ::__toString
     */
    public function testCode()
    {
        $currency = Currency::fromIsoCode('EUR');
        $this->assertEquals('EUR', $currency->getIsoCode());
        $this->assertEquals('EUR', (string) $currency);
    }

    /**
     * @covers ::equals
     */
    public function testDifferentInstancesAreEqual()
    {
        $c1 = Currency::fromIsoCode('EUR');
        $c2 = Currency::fromIsoCode('EUR');
        $c3 = Currency::fromIsoCode('USD');
        $c4 = Currency::fromIsoCode('USD');
        $this->assertTrue($c1->equals($c2));
        $this->assertTrue($c3->equals($c4));
    }

    /**
     * @covers ::equals
     */
    public function testDifferentCurrenciesAreNotEqual()
    {
        $c1 = Currency::fromIsoCode('EUR');
        $c2 = Currency::fromIsoCode('USD');
        $this->assertFalse($c1->equals($c2));
    }

    /**
     * @covers ::equals
     */
    public function testToUpper()
    {
        $c1 = Currency::fromIsoCode('EUR');
        $c2 = Currency::fromIsoCode('eur');
        $this->assertTrue($c1->equals($c2));
    }

    /**
     * @expectedException \Celtric\UbMoney\InvalidArgumentException
     */
    public function testNonStringCode()
    {
        Currency::fromIsoCode(1234);
    }

    /**
     * @expectedException \Celtric\UbMoney\InvalidArgumentException
     */
    public function testNon3LetterCode()
    {
        Currency::fromIsoCode('FooBar');
    }
}
