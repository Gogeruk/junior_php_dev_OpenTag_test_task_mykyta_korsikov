<?php

namespace App\Controller;

use App\API\Service\JsdelivrNetGhFawazahmedProcessor;
use App\Form\CurrencyExchangeOperationType;
use App\Repository\CurrencyExchangeOperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


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
        Request                          $request,
        JsdelivrNetGhFawazahmedProcessor $jsdelivrNetGhFawazahmedProcessor
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

            list($exchangeRate, $trend) = $jsdelivrNetGhFawazahmedProcessor->apiProcessor(
                $currencyConversionFrom,
                $currencyConversionTo,
            );

            // display
            return $this->render('currency_exchange/display_new_exchange_result.html.twig', [
                'currencyConversion' => [
                    'currencyConversionFrom' => $currencyConversionFrom,
                    'currencyConversionTo' => $currencyConversionTo,
                    'exchangeRate' => $exchangeRate,
                    'trend' => $trend,
                ],
            ]);
        }

        return $this->renderForm('currency_exchange/new.html.twig', [
            'form' => $form,
            'failed_to_exchange' => false
        ]);
    }
}
