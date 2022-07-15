<?php

namespace App\Components;

use App\Entity\InstagramUser;
use App\Form\CurrencyExchangeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('instagram_user_form')]
class CurrencyExchangeFormComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    /**
     * @var InstagramUser|null
     */
    #[LiveProp(fieldName: 'initialFormData')]
    public ?InstagramUser $InstagramUser = null;

    /**
     * @var string
     */
    #[LiveProp]
    public string $buttonLabel = 'Parse';

    /**
     * @return FormInterface
     * Used to re-create the CurrencyExchangeType form for re-rendering.
     */
    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CurrencyExchangeType::class, $this->InstagramUser);
    }
}
