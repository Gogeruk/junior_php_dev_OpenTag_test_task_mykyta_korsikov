<?php

namespace App\API\Service;

use App\API\Adapter\JsdelivrNetGhFawazahmedAdapter;
use App\Currency\TrendService;

class JsdelivrNetGhFawazahmedProcessor
{
    /**
     * @var SaveCurrencyExchangeOperation
     */
    public $saveCurrencyExchangeOperation;

    /**
     * @var JsdelivrNetGhFawazahmedService
     */
    public $jsdelivrNetGhFawazahmedService;

    /**
     * @var TrendService
     */
    public $trendService;


    /**
     * @param SaveCurrencyExchangeOperation $saveCurrencyExchangeOperation
     * @param JsdelivrNetGhFawazahmedService $jsdelivrNetGhFawazahmedService
     * @param TrendService $trendService
     */
    public function __construct
    (
        SaveCurrencyExchangeOperation  $saveCurrencyExchangeOperation,
        JsdelivrNetGhFawazahmedService $jsdelivrNetGhFawazahmedService,
        TrendService                   $trendService
    )
    {
        $this->saveCurrencyExchangeOperation = $saveCurrencyExchangeOperation;
        $this->jsdelivrNetGhFawazahmedService = $jsdelivrNetGhFawazahmedService;
        $this->trendService = $trendService;
    }


    /**
     * @param string $currencyConversionFrom
     * @param string $currencyConversionTo
     * @return array
     */
    public function apiProcessor
    (
        string $currencyConversionFrom,
        string $currencyConversionTo
    ) : array
    {

        // !!!!
        // check if exchange rate is stored in cache


        // no?
        if (true) {

            // get exchange rate from api
            $exchange = new JsdelivrNetGhFawazahmedAdapter($this->jsdelivrNetGhFawazahmedService);
            $exchangeRate = $exchange->exchange($currencyConversionFrom, $currencyConversionTo)[$currencyConversionTo];

            // !!!!
            // cache array data for an hour


            // save data to db
            $this->saveCurrencyExchangeOperation->save(
                $currencyConversionFrom,
                $currencyConversionTo,
                $exchangeRate
            );
        }


        // calc the trend
        $trend = $this->trendService->getTrend($exchangeRate);


        return [$exchangeRate, $trend];
    }




}