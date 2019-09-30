<?php

namespace AppBundle\Form;

use AppBundle\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RecipeType extends ApplicationType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
            'label' => 'Titre',
            'constraints' => [
                new NotBlank(['message' => 'Vous devez mettre un titre !']),
            ],
        ])
            ->add('subTitle', TextType::class, [
                'label' => 'Sous Titre',
                'required' => false,
            ])
            ->add('ingredient1', ChoiceType::class,
                $this->getConfiguration([
                    'constraints' => [
                        new NotBlank(['message' => 'Vous devez remplir au moins 1 ingredient !']),
                    ],
                    'label' => 'Ingredient n°1',
                    'placeholder' => 'choisissez votre ingredient...',
                ]))
            ->add('ingredient2', ChoiceType::class,
                $this->getConfiguration([
                    'label' => 'Ingredient n°2',
                    'placeholder' => 'choisissez votre ingredient...',
                    'required' => false,
                ]))
            ->add('ingredient3', ChoiceType::class,
                $this->getConfiguration([
                    'label' => 'Ingredient n°3',
                    'placeholder' => 'choisissez votre ingredient...',
                    'required' => false,
                ]))
            ->add('ingredient4', ChoiceType::class,
                $this->getConfiguration([
                    'label' => 'Ingredient n°4',
                    'placeholder' => 'choisissez votre ingredient...',
                    'required' => false,
                ]))
            ->add('ingredient5', TextType::class, [
                'attr' => [
                    'placeholder' => 'entrer votre ingredient supplementaire',
                ],
                'label' => 'Besoin d\'un ingredient supplémentaire ?',
                'required' => false,
                'mapped' => false,
            ])
            ->add('picture', FileType::class, [
                'label' => 'Photo de votre recette',
                'required' => false,
                'data_class' => null,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Recipe',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_recipe';
    }

}
