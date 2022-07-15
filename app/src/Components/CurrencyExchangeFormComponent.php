<?php

namespace App\Components;

use App\Entity\CurrencyExchangeOperation;
use App\Form\CurrencyExchangeOperationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('currency_exchange_form')]
class CurrencyExchangeFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    /**
     * @var CurrencyExchangeOperation|null
     */
    #[LiveProp(fieldName: 'initialFormData')]
    public ?CurrencyExchangeOperation $CurrencyExchangeOperation = null;

    /**
     * @var string
     */
    #[LiveProp]
    public string $buttonLabel = 'Currency';

    /**
     * @return FormInterface
     * Used to re-create the CurrencyExchangeOperationType form for re-rendering.
     */
    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CurrencyExchangeOperationType::class, $this->CurrencyExchangeOperation);
    }
}
