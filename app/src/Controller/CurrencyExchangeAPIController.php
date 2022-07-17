<?php

namespace App\Controller;

use App\API\Service\JsdelivrNetGhFawazahmedProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;


class CurrencyExchangeAPIController extends AbstractController
{
    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator) {
        $this->validator = $validator;
    }

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

            $constraints = new Assert\Collection([
                'from' => [new Assert\Currency(), new Assert\NotBlank()],
                'to' => [new Assert\Currency(), new Assert\NotBlank()],
            ]);

            $validationResult = $this->validator->validate($parameters, $constraints);
            if ($validationResult->count() !== 0) {
                return $response->setContent($validationResult);
            }

            list($exchangeRate, $trend) = $jsdelivrNetGhFawazahmedProcessor->apiProcessor(
                strtolower($parameters['from']),
                strtolower($parameters['to']),
            );

            return $response->setContent($exchangeRate . ' ' . $trend);
        }

        return $response->setContent("Error");
    }
}
