<?php

namespace App\Form;

use App\Entity\ClientOrder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class ClientOrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateheureCommande', null, [
                'label' => 'Date de la commande',
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('statutCommande', ChoiceType::class, [
                'choices' => [
                    'Prise' => 'Prise',
                    'Préparée' => 'Préparée',
                    'Servie' => 'Servie',
                    'Payée' => 'Payée',
                ]
            ])
            ->add('tableCommande')
            ->add('Serveur')
            ->add('Plats')
            ->add('Client')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ClientOrder::class,
        ]);
    }
}
