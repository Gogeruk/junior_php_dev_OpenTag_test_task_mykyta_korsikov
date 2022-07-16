<?php

namespace App\API\Service;

use App\Entity\CurrencyExchangeOperation;
use Doctrine\ORM\EntityManagerInterface;

class SaveCurrencyExchangeOperation
{
    /**
     * @var EntityManagerInterface
     */
    public $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @param string $currencyConversionFrom
     * @param string $currencyConversionTo
     * @param float $exchangeRate
     * @return void
     */
    public function save
    (
        string $currencyConversionFrom,
        string $currencyConversionTo,
        float $exchangeRate,
    ) : void
    {
        $currencyExchangeOperation = new CurrencyExchangeOperation();
        $currencyExchangeOperation->setCurrencyConversionFrom($currencyConversionFrom);
        $currencyExchangeOperation->setCurrencyConversionTo($currencyConversionTo);
        $currencyExchangeOperation->setResultingExchangeRate($exchangeRate);
        $currencyExchangeOperation->setTimestamp(new \DateTime());

        $this->em->flush();
        $this->em->clear();
    }
}