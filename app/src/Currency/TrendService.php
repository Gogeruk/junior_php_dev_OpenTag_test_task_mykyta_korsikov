<?php

namespace App\Currency;

use App\Repository\CurrencyExchangeOperationRepository;

class TrendService
{

    /**
     * @param CurrencyExchangeOperationRepository $currencyExchangeOperationRepository
     */
    public function __construct
    (
        CurrencyExchangeOperationRepository $currencyExchangeOperationRepository
    )
    {
        $this->currencyExchangeOperationRepository = $currencyExchangeOperationRepository;
    }


    /**
     * @param float $currentRate
     * @param int $scope
     * @return string|null
     */
    public function getTrend
    (
        float $currentRate,
        int   $scope = 10
    ) : null|string
    {
        // get last 10 currency exchange rates
        $currencyScope = $this
            ->currencyExchangeOperationRepository
            ->getResultingExchangeRateWithLimit($scope)
        ;

        // fewer rows in db than scope
        if (count($currencyScope) < $scope) {
            return null;
        }

        $rates = [];
        foreach ($currencyScope as $rate) {
            $rates[] = $rate['resultingExchangeRate'];
        }

        // calculate trend
        list($avrRate, $deviation) = $this->calculateTrend($rates, $currentRate);

        // convert numbers to trend symbols
        $deviationPercentage = $deviation * 100;
        return $this->convertNumbersToTrendSymbols($deviationPercentage);
    }


    /**
     * @param array $rates
     * @param float $currentRate
     * @return array
     *
     * The trend should be calculated
     * as the deviation between the current rate
     * and average of the last 10 rates
     * (calculation should be simple, avoid usage of statistics formulas).
     */
    public function calculateTrend(array $rates, float $currentRate): array
    {
        $avrRate = array_sum($rates) / count($rates);
        $deviation = $currentRate / $avrRate;
        return array($avrRate, $deviation);
    }


    /**
     * @param float|int $deviationPercentage
     * @return string
     */
    public function convertNumbersToTrendSymbols(float|int $deviationPercentage): string
    {
        if ($deviationPercentage > 100) {
            return '↑';
        }
        if ($deviationPercentage < 100) {
            return '↓';
        }
        return '-';
    }
}