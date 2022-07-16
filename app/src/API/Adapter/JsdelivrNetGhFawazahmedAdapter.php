<?php

namespace App\API\Adapter;

use App\API\Interfaces\ExchangeRateInterface;
use App\API\Service\JsdelivrNetGhFawazahmedService;

class JsdelivrNetGhFawazahmedAdapter implements ExchangeRateInterface
{

    /**
     * @var JsdelivrNetGhFawazahmedService
     */
    protected $jsdelivrNetGhFawazahmedService;


    /**
     * @param JsdelivrNetGhFawazahmedService $jsdelivrNetGhFawazahmedService
     */
    public function __construct(JsdelivrNetGhFawazahmedService $jsdelivrNetGhFawazahmedService)
    {
        $this->jsdelivrNetGhFawazahmedService = $jsdelivrNetGhFawazahmedService;
    }


    /**
     * @param string $currencyConversionFrom
     * @param string $currencyConversionTo
     * @return array|float|int|string|null
     */
    public function exchange
    (
        string $currencyConversionFrom,
        string $currencyConversionTo
    )
    {
        return $this->jsdelivrNetGhFawazahmedService->exchangeTwoCurrencies(
            $currencyConversionFrom,
            $currencyConversionTo
        );
    }
}
