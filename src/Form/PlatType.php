<?php

namespace App\Form;

use App\Controller\PlatController;
use App\Entity\Allergen;
use App\Entity\Category;
use App\Entity\Plat;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Name', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new Length([
                        'max' => 100,
                    ]),
                ],
            ])
            //appeler en option la fonction _availableCalories() pour la liste des calories
            ->add('Calories', ChoiceType::class, ['choices' => $this->_availableCalories()])
            ->add('Price', NumberType::class, array('invalid_message' => 'Vous devez saisir un nombre !'))
            ->add('Image', FileType::class, array('data_class' => null))
            ->add('Description', TextType::class, array('invalid_message' => 'Vous devez saisir au moins 10 caractères !', 'attr' => array('minlength' => 10, 'maxlength' => 100)))
            ->add('Sticky', ChoiceType::class, array(
                'choices' => array(
                    'Oui' => true,
                    'Non' => false,
                ),
                'placeholder' => 'Choisissez une option',
            ))
            ->add('Category', EntityType::class, array(
                'class' => Category::class,
                'choice_label' => 'Name',
                'placeholder' => 'Choisissez une catégorie',
            ))
            ->add('User', EntityType::class, array(
                'class' => User::class,
                'choice_label' => 'Username',
                'placeholder' => 'Choisissez un utilisateur',
            ))
            ->add('allergens', EntityType::class, array(
                'class' => Allergen::class,
                'choice_label' => 'name',
                'multiple' => true,
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }

    private function _availableCalories()
    {
        $calories = [];
        for ($i = 10; $i <= 300; $i += 10) {
            $calories[$i] = $i;
        }
        return $calories;
    }
}
