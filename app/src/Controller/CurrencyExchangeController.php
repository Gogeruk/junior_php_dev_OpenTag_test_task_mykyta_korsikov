<?php

namespace App\Controller;

use App\Form\CurrencyExchangeOperationType;
use App\Repository\CurrencyExchangeOperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


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
        Request                 $request,
        ParameterBagInterface   $parameterBag,
    ): Response
    {
        $form = null;
        if ($request->isMethod('POST')) {
            $form = $this->createForm(CurrencyExchangeOperationType::class);
            $form->handleRequest($request);
        }

        if ($form && $form->isSubmitted() && $form->isValid()) {

            $currencyConversionFrom = $form->getData()->getCurrencyConversionFrom();
            $currencyConversionTo = $form->getData()->getCurrencyConversionTo();


            ////////////// THIS NEEDS TO BE ITS OWN METHOD FOR API //////////////

            // get Currency Conversion data from yaml or if no yaml do api call
            // use adapter for api



            // get Exchange rate
            $exchangeRate = 1.0;



            // calc $trend
            $trend = null;

            ////////////// THIS NEEDS TO BE ITS OWN METHOD FOR API //////////////



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
