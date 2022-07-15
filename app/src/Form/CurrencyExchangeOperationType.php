<?php


namespace App\Form;

use App\Entity\CurrencyExchangeOperation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Currency;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class CurrencyExchangeOperationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $options =
        [
            'error_bubbling' => true,
            'trim' => false,
            'label_attr' => ['class' => 'text-white'],
            'attr' => [
                'class'  => 'form-control border border-primary form-control-sm bg-dark text-white m-1',
                'placeholder' => 'USD'
            ],
            'constraints' => [
                new NotBlank(),
                new NotNull(),
                new Currency(),
            ]
        ];

        $builder
            ->add
            (
                'currencyConversionFrom',
                TextType::class,
                $options
            )
            ->add
            (
                'currencyConversionTo',
                TextType::class,
                $options
            )
        ;
    }


    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CurrencyExchangeOperation::class,
        ]);
    }
}
