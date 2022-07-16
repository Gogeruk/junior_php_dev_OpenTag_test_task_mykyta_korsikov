<?php

namespace App\API\Interfaces;

interface ExchangeRateInterface
{
    /**
     * @param string $currencyConversionFrom
     * @param string $currencyConversionTo
     * @return mixed
     */
    public function exchange
    (
        string $currencyConversionFrom,
        string $currencyConversionTo
    );
}
