<?php

namespace App\API\Service;

use App\API\Adapter\JsdelivrNetGhFawazahmedAdapter;
use App\RequestService\CustomRequestService;

class JsdelivrNetGhFawazahmedProcessor
{

    /**
     * @param string $currencyConversionFrom
     * @param string $currencyConversionTo
     * @return string
     */
    public function apiProcessor
    (
        string $currencyConversionFrom,
        string $currencyConversionTo
    )
    {

        // check if exchange rate is stored in cache

        // no?
        if (true) {

            // get exchange rate from api
            $exchange = new JsdelivrNetGhFawazahmedAdapter(new JsdelivrNetGhFawazahmedService(new CustomRequestService()));
            $exchangeRate = $exchange->exchange($currencyConversionFrom, $currencyConversionTo)[$currencyConversionTo];

            // cache array data for an hour

            // save data to db
            $save = new SaveCurrencyExchangeOperation();
            $save->save();
        }






        // calc the trend
        $trend = $trendService->getTrend($exchangeRate);


        return $exchangeRate . ' ' . $trend;
    }




}