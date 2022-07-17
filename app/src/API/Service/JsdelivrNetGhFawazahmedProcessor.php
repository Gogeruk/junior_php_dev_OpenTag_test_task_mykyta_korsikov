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

        // does cache exchange rate exist?
        if (!is_float($exchangeRate)) {

            // get exchange rate from api
            $exchange = new JsdelivrNetGhFawazahmedAdapter($this->jsdelivrNetGhFawazahmedService);
            $exchangeRate = $exchange->exchange($currencyConversionFrom, $currencyConversionTo)[$currencyConversionTo];

            // save new cache
            $exchangeRateValues = [
                'stats.exchange_rate' => $exchangeRate,
            ];
            $cache->warmUp($exchangeRateValues);

            // set cache expiration date
            $now = new \DateTime();
            $inOneHour = (clone $now)->add(new \DateInterval("PT1H"));
            $cache->getItem('stats.exchange_rate')->expiresAt($inOneHour);
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