<?php

namespace App\Controller;

use App\API\Service\JsdelivrNetGhFawazahmedProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Currency;


class CurrencyExchangeAPIController extends AbstractController
{
    /**
     * @Route("/api/currency/new", name="app_api_currency_new")
     */
    public function apiCurrencyExchangeOperation
    (
        Request                          $request,
        JsdelivrNetGhFawazahmedProcessor $jsdelivrNetGhFawazahmedProcessor
    ): Response
    {
        $response = new Response();
        if ($request->isMethod('POST')) {
            $parameters = json_decode($request->getContent(), true);

            list($exchangeRate, $trend) = $jsdelivrNetGhFawazahmedProcessor->apiProcessor(
                strtolower($parameters['from']),
                strtolower($parameters['to']),
            );

            return $response->setContent($exchangeRate . ' ' . $trend);
        }

        return $response->setContent("Error");
    }
}
