<?php

namespace App\API\Service;

use App\RequestService\CustomRequestService;

class JsdelivrNetGhFawazahmedService
{

    /**
     * @var CustomRequestService
     */
    public $customRequestService;


    /**
     * @param CustomRequestService $customRequestService
     */
    public function __construct
    (
        CustomRequestService $customRequestService
    )
    {
        $this->customRequestService = $customRequestService;
    }


    /**
     * @param string $currencyConversionFrom
     * @param string $currencyConversionTo
     * @return array|null
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     *
     * source:
     * https://github.com/fawazahmed0/currency-api
     */
    public function exchangeTwoCurrencies
    (
        string $currencyConversionFrom,
        string $currencyConversionTo
    ) : array|null
    {
        $apiUrl =
            'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/' .
            $currencyConversionFrom .
            '/' .
            $currencyConversionTo .
            '.json'
        ;

        $exchangeRate = json_decode(
            $this->customRequestService->getHtml($apiUrl),
            true
        );

        if (!is_array($exchangeRate)) {
            return null;
        }
        return $exchangeRate;
    }
}
