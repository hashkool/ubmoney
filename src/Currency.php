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

class Currency
{
    /**
     * Currency identifying 3 letter ISO code
     *
     * @var string
     */
    private $isoCode;

    /**
     * @param string $isoCode
     */
    public function __construct($isoCode)
    {
        if (!is_string($isoCode) || strlen($isoCode) !== 3) {
            throw new InvalidArgumentException(
                'Currency code should be 3 letter ISO code'
            );
        }

        $this->isoCode = strtoupper($isoCode);
    }

    /**
     * Creates a Currency from its ISO code
     *
     * @param string $isoCode
     *
     * @return static
     */
    public static function fromIsoCode($isoCode)
    {
        return new static($isoCode);
    }

    /**
     * Returns the currency ISO code
     *
     * @return string
     */
    public function getIsoCode()
    {
        return $this->isoCode;
    }

    /**
     * Checks whether this currency is the same as an other
     *
     * @param Currency $other
     *
     * @return boolean
     */
    public function equals(Currency $other)
    {
        return $this->isoCode === $other->isoCode;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->isoCode;
    }
}
