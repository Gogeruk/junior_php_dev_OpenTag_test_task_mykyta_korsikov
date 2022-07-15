<?php

namespace App\Entity;

use App\Repository\CurrencyExchangeOperationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyExchangeOperationRepository::class)]
class CurrencyExchangeOperation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private ?string $currencyConversionFrom = null;

    #[ORM\Column(length: 3)]
    private ?string $currencyConversionTo = null;

    #[ORM\Column]
    private ?float $resultingExchangeRate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrencyConversionFrom(): ?string
    {
        return $this->currencyConversionFrom;
    }

    public function setCurrencyConversionFrom(string $currencyConversionFrom): self
    {
        $this->currencyConversionFrom = $currencyConversionFrom;

        return $this;
    }

    public function getCurrencyConversionTo(): ?string
    {
        return $this->currencyConversionTo;
    }

    public function setCurrencyConversionTo(string $currencyConversionTo): self
    {
        $this->currencyConversionTo = $currencyConversionTo;

        return $this;
    }

    public function getResultingExchangeRate(): ?float
    {
        return $this->resultingExchangeRate;
    }

    public function setResultingExchangeRate(float $resultingExchangeRate): self
    {
        $this->resultingExchangeRate = $resultingExchangeRate;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }
}
