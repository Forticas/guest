<?php

namespace App\Form;

use App\Entity\Guest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la personne',
                'attr' => [
                    'placeholder' => 'Nom de la personne',
                ],
                'required' => true,
            ])
            ->add('guestsNumber', NumberType::class, [
                'label' => 'Nombre d\'invités',
                'attr' => [
                    'min' => 1,
                    'max' => 10,
                ],
                'required' => true,
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    Guest::STATUS_PENDING => 'Pending',
                    Guest::STATUS_ACCEPTED => 'Accepted',
                    Guest::STATUS_REJECTED => 'Rejected',
                ],
                'required' => true,
            ])
            ->add('phoneNumber', TextType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Numéro de téléphone',
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guest::class,
        ]);
    }
}
