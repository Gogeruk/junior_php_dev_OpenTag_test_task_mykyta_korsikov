<?php

namespace App\Controller;

use App\API\Adapter\JsdelivrNetGhFawazahmedAdapter;
use App\API\Service\JsdelivrNetGhFawazahmedService;
use App\Form\CurrencyExchangeOperationType;
use App\Repository\CurrencyExchangeOperationRepository;
use App\RequestService\CustomRequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Currency\TrendService;


class CurrencyExchangeController extends AbstractController
{
    /**
     * @Route("/currency/index", name="app_currency_index")
     */
    public function index(CurrencyExchangeOperationRepository $currencyExchangeOperationRepository): Response
    {
        return $this->render('currency_exchange/index.html.twig', [
            'currencyConversions' => $currencyExchangeOperationRepository->findAll(),
        ]);
    }


    /**
     * @Route("/currency/new", name="app_currency_new")
     */
    public function parseCurrencyExchangeOperation
    (
        Request      $request,
        TrendService $trendService
    ): Response
    {
        $form = null;
        if ($request->isMethod('POST')) {
            $form = $this->createForm(CurrencyExchangeOperationType::class);
            $form->handleRequest($request);
        }

        if ($form && $form->isSubmitted() && $form->isValid()) {

            $currencyConversionFrom = strtolower($form->getData()->getCurrencyConversionFrom());
            $currencyConversionTo = strtolower($form->getData()->getCurrencyConversionTo());



            ////////////// THIS NEEDS TO BE ITS OWN METHOD FOR API //////////////

            // check if exchange rate is stored in cache

            // no?
            // get exchange rate from api
            $exchange = new JsdelivrNetGhFawazahmedAdapter(
                new JsdelivrNetGhFawazahmedService(
                    new CustomRequestService()
                )
            );

            $exchangeRate = $exchange->exchange($currencyConversionFrom, $currencyConversionTo)[$currencyConversionTo];


            // cache array data for an hour


            // save data to db



            // calc the trend
            $trend = $trendService->getTrend($exchangeRate);


            var_dump($trend);
            exit();
            ////////////// THIS NEEDS TO BE ITS OWN METHOD FOR API //////////////



            // dont go to index
            // make a unique display
            return $this->render('currency_exchange/index.html.twig', [
                'currencyConversions' => [
                    $currencyConversionFrom,
                    $currencyConversionTo,
                    $exchangeRate,
                    $trend
                ],
            ]);
        }

        return $this->renderForm('currency_exchange/new.html.twig', [
            'form' => $form,
            'failed_to_exchange' => false
        ]);
    }
}
