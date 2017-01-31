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
 * @coversDefaultClass Celtric\UbMoney\Money
 * @uses Celtric\UbMoney\Currency
 * @uses Celtric\UbMoney\Money
 */
final class MoneyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__callStatic
     */
    public function testFactoryMethod()
    {
        $money = Money::EUR(25);
        $this->assertInstanceOf('Celtric\UbMoney\Money', $money);
    }

    /**
     * @covers ::fromAmount
     */
    public function testFromAmountAndCurrency()
    {
        $money = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $this->assertInstanceOf('Celtric\UbMoney\Money', $money);
    }

    /**
     * @covers ::fromAmount
     */
    public function testStaticConstructorAcceptsCurrencyAsString()
    {
        $money = Money::fromAmount('100', 'EUR');
        $this->assertInstanceOf('Celtric\UbMoney\Money', $money);
    }

    public function testNumericValues()
    {
        $money = Money::EUR('100');

        $this->assertTrue($money->equals(Money::EUR(100)));
        $this->assertTrue($money->equals(Money::EUR(100.00)));
        $this->assertTrue($money->equals(Money::EUR('100.000000')));
    }

    /**
     * @expectedException \Celtric\UbMoney\InvalidArgumentException
     */
    public function testNonNumericStringsThrowException()
    {
        Money::EUR('Foo');
    }

    /**
     * @covers ::amount
     * @covers ::currency
     */
    public function testGetters()
    {
        $euro = Currency::fromIsoCode('EUR');
        $money = Money::fromAmount('100', $euro);
        $this->assertEquals('100', $money->amount());
        $this->assertEquals($euro, $money->currency());
    }

    /**
     * @covers ::add
     */
    public function testAddition()
    {
        $m1 = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $m2 = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $sum = $m1->add($m2);
        $expected = Money::fromAmount('200', Currency::fromIsoCode('EUR'));

        $this->assertTrue($sum->equals($expected));

        // Should return a new instance
        $this->assertNotSame($sum, $m1);
        $this->assertNotSame($sum, $m2);
    }

    /**
     * @covers ::add
     */
    public function testAdditionWithDecimals()
    {
        $m1 = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $m2 = Money::fromAmount('0.01', Currency::fromIsoCode('EUR'));
        $sum = $m1->add($m2);
        $expected = Money::fromAmount('100.01', Currency::fromIsoCode('EUR'));

        $this->assertTrue($sum->equals($expected));
    }

    /**
     * @expectedException \Celtric\UbMoney\InvalidArgumentException
     */
    public function testDifferentCurrenciesCannotBeAdded()
    {
        $m1 = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $m2 = Money::fromAmount('100', Currency::fromIsoCode('USD'));
        $m1->add($m2);
    }

    /**
     * @covers ::subtract
     */
    public function testSubtraction()
    {
        $m1 = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $m2 = Money::fromAmount('200', Currency::fromIsoCode('EUR'));
        $diff = $m1->subtract($m2);
        $expected = Money::fromAmount('-100', Currency::fromIsoCode('EUR'));

        $this->assertTrue($diff->equals($expected));

        // Should return a new instance
        $this->assertNotSame($diff, $m1);
        $this->assertNotSame($diff, $m2);
    }

    /**
     * @covers ::subtract
     */
    public function testSubtractionWithDecimals()
    {
        $m1 = Money::fromAmount('100.01', Currency::fromIsoCode('EUR'));
        $m2 = Money::fromAmount('200', Currency::fromIsoCode('EUR'));
        $diff = $m1->subtract($m2);
        $expected = Money::fromAmount('-99.99', Currency::fromIsoCode('EUR'));

        $this->assertTrue($diff->equals($expected));
    }

    /**
     * @expectedException \Celtric\UbMoney\InvalidArgumentException
     */
    public function testDifferentCurrenciesCannotBeSubtracted()
    {
        $m1 = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $m2 = Money::fromAmount('100', Currency::fromIsoCode('USD'));
        $m1->subtract($m2);
    }

    /**
     * @covers ::multiplyBy
     */
    public function testMultiplication()
    {
        $money = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $expected1 = Money::fromAmount('200', Currency::fromIsoCode('EUR'));
        $expected2 = Money::fromAmount('101', Currency::fromIsoCode('EUR'));

        $this->assertTrue($money->multiplyBy(2)->equals($expected1));
        $this->assertTrue($money->multiplyBy('1.01')->equals($expected2));

        $this->assertNotSame($money, $money->multiplyBy(2));
    }

    /**
     * @expectedException \Celtric\UbMoney\InvalidArgumentException
     */
    public function testInvalidMultiplicationOperand()
    {
        $money = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $money->multiplyBy('operand');
    }

    /**
     * @covers ::divideBy
     */
    public function testDivision()
    {
        $money = Money::fromAmount('30', Currency::fromIsoCode('EUR'));
        $expected1 = Money::fromAmount('15', Currency::fromIsoCode('EUR'));
        $expected2 = Money::fromAmount('3.33333333333', Currency::fromIsoCode('EUR'));
        $expected3 = Money::fromAmount('-3', Currency::fromIsoCode('EUR'));

        $this->assertTrue($money->divideBy(2)->equals($expected1));
        $this->assertTrue($money->divideBy(9)->equals($expected2));
        $this->assertTrue($money->divideBy(-10)->equals($expected3));

        $this->assertNotSame($money, $money->divideBy(2));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDivisorIsNumericZero()
    {
        $money = Money::fromAmount('30', Currency::fromIsoCode('EUR'));
        $money->divideBy(0)->amount();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDivisorIsFloatZero()
    {
        $money = Money::fromAmount('30', Currency::fromIsoCode('EUR'));
        $money->divideBy(0.0)->amount();
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testDivisorIsStringZero()
    {
        $money = Money::fromAmount('30', Currency::fromIsoCode('EUR'));
        $money->divideBy('0')->amount();
    }

    /**
     * @covers ::round
     */
    public function testRoundWithoutRounding()
    {
        $money = Money::fromAmount('3.33333333333', Currency::fromIsoCode('EUR'));
        $expected1 = Money::fromAmount('3', Currency::fromIsoCode('EUR'));
        $expected2 = Money::fromAmount('3.33', Currency::fromIsoCode('EUR'));

        $this->assertTrue($money->round()->equals($expected1));
        $this->assertTrue($money->round(2)->equals($expected2));

        $this->assertNotSame($money, $money->round());
    }

    /**
     * @covers ::round
     */
    public function testRoundWithRounding()
    {
        $money = Money::fromAmount('3.9843', Currency::fromIsoCode('EUR'));
        $expected1 = Money::fromAmount('4', Currency::fromIsoCode('EUR'));
        $expected2 = Money::fromAmount('3.98', Currency::fromIsoCode('EUR'));

        $this->assertTrue($money->round()->equals($expected1));
        $this->assertTrue($money->round(2)->equals($expected2));

        $this->assertNotSame($money, $money->round());
    }

    /**
     * @covers ::convertTo
     */
    public function convertTo()
    {
        $money = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $usd = Currency::fromIsoCode('USD');

        $expected = Money::fromAmount('150', $usd);

        $this->assertTrue($money->convertTo($usd, '1.50')->equals($expected));
    }

    /**
     * @covers ::isGreaterThan
     * @covers ::isGreaterThanOrEqualTo
     * @covers ::isLessThan
     * @covers ::isLessThanOrEqualTo
     * @covers ::equals
     */
    public function testComparison()
    {
        $euro1 = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $euro2 = Money::fromAmount('200', Currency::fromIsoCode('EUR'));
        $euro3 = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $euro4 = Money::fromAmount('0', Currency::fromIsoCode('EUR'));
        $euro5 = Money::fromAmount('-100', Currency::fromIsoCode('EUR'));
        $euro6 = Money::fromAmount('1.1111', Currency::fromIsoCode('EUR'));
        $euro7 = Money::fromAmount('1.2222', Currency::fromIsoCode('EUR'));

        $this->assertTrue($euro2->isGreaterThan($euro1));
        $this->assertFalse($euro1->isGreaterThan($euro2));
        $this->assertTrue($euro1->isLessThan($euro2));
        $this->assertFalse($euro2->isLessThan($euro1));
        $this->assertTrue($euro1->equals($euro3));
        $this->assertFalse($euro1->equals($euro2));
        $this->assertFalse($euro6->equals($euro7));

        $this->assertTrue($euro1->isGreaterThanOrEqualTo($euro3));
        $this->assertTrue($euro1->isLessThanOrEqualTo($euro3));

        $this->assertFalse($euro1->isGreaterThanOrEqualTo($euro2));
        $this->assertFalse($euro1->isLessThanOrEqualTo($euro4));

        $this->assertTrue($euro4->isLessThanOrEqualTo($euro1));
        $this->assertTrue($euro4->isGreaterThanOrEqualTo($euro5));

        $this->assertTrue($euro6->isLessThanOrEqualTo($euro7));
    }

    /**
     * @covers ::isPositive
     * @covers ::isNegative
     * @covers ::isZero
     */
    public function testPositivity()
    {
        $euro1 = Money::fromAmount('100', Currency::fromIsoCode('EUR'));
        $euro2 = Money::fromAmount('0', Currency::fromIsoCode('EUR'));
        $euro3 = Money::fromAmount('-100', Currency::fromIsoCode('EUR'));
        $euro4 = Money::fromAmount('0.0001', Currency::fromIsoCode('EUR'));

        $this->assertTrue($euro1->isPositive());
        $this->assertFalse($euro1->isNegative());
        $this->assertFalse($euro1->isZero());

        $this->assertTrue($euro2->isZero());
        $this->assertFalse($euro2->isNegative());
        $this->assertFalse($euro2->isPositive());

        $this->assertTrue($euro3->isNegative());
        $this->assertFalse($euro3->isPositive());
        $this->assertFalse($euro3->isZero());

        $this->assertFalse($euro4->isZero());
    }

    /**
     * @expectedException \Celtric\UbMoney\InvalidArgumentException
     */
    public function testDifferentCurrenciesCannotBeCompared()
    {
        Money::EUR(1)->equals(Money::USD(1));
    }

    /**
     * @covers ::hasSameCurrencyAs
     */
    public function testHasSameCurrencyAs()
    {
        $this->assertTrue(Money::EUR(1)->hasSameCurrencyAs(Money::EUR(100)));
        $this->assertTrue(Money::EUR(1)->hasSameCurrencyAs(Money::EUR(1)));
        $this->assertFalse(Money::EUR(1)->hasSameCurrencyAs(Money::USD(1)));
    }
}
