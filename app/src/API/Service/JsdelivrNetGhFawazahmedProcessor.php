<?php

namespace App\API\Service;

use App\API\Adapter\JsdelivrNetGhFawazahmedAdapter;
use App\Currency\TrendService;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


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
     * @var ParameterBagInterface
     */
    public $parameterBag;

    /**
     * @var TrendService
     */
    public $trendService;


    /**
     * @param SaveCurrencyExchangeOperation $saveCurrencyExchangeOperation
     * @param JsdelivrNetGhFawazahmedService $jsdelivrNetGhFawazahmedService
     * @param ParameterBagInterface $parameterBag
     * @param TrendService $trendService
     */
    public function __construct
    (
        SaveCurrencyExchangeOperation  $saveCurrencyExchangeOperation,
        JsdelivrNetGhFawazahmedService $jsdelivrNetGhFawazahmedService,
        ParameterBagInterface          $parameterBag,
        TrendService                   $trendService
    )
    {
        $this->saveCurrencyExchangeOperation = $saveCurrencyExchangeOperation;
        $this->jsdelivrNetGhFawazahmedService = $jsdelivrNetGhFawazahmedService;
        $this->parameterBag = $parameterBag;
        $this->trendService = $trendService;
    }


    /**
     * @param string $currencyConversionFrom
     * @param string $currencyConversionTo
     * @return array
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function apiProcessor
    (
        string $currencyConversionFrom,
        string $currencyConversionTo
    ) : array
    {
        // get cache
        $cacheFileName = $currencyConversionFrom . '_' . $currencyConversionTo . '.cache';
        $cache = new PhpArrayAdapter(
            $this->parameterBag->get('kernel.project_dir') . '/cache/'. $cacheFileName,
            new FilesystemAdapter()
        );
        $exchangeRate = $cache->getItem('stats.exchange_rate')->get();
        $expirationDate = $cache->getItem('stats.expiration_date')->get();
        $now = new \DateTime();

        // does cache exchange rate exist and is it expireD?
        if (
            !is_float($exchangeRate) && $exchangeRate !== 1 or
            clone $now > $expirationDate
        ) {
            // get exchange rate from api
            $exchange = new JsdelivrNetGhFawazahmedAdapter($this->jsdelivrNetGhFawazahmedService);
            $exchangeRate = $exchange->exchange($currencyConversionFrom, $currencyConversionTo)[$currencyConversionTo];

            // save new cache
            $expirationDate = (clone $now)->add(new \DateInterval("PT1H")); // in 1 hour

            $exchangeRateValues = [
                'stats.exchange_rate'   => $exchangeRate,
                'stats.expiration_date' => $expirationDate,
            ];
            $cache->warmUp($exchangeRateValues);
        }

        // save data to db for trend
        $this->saveCurrencyExchangeOperation->save(
            $currencyConversionFrom,
            $currencyConversionTo,
            $exchangeRate
        );

        // calc the trend
        $trend = $this->trendService->getTrend($exchangeRate);

        return [$exchangeRate, $trend];
    }
}