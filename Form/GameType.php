<?php

namespace Aldaflux\GameQuizzBundle\Form;

use Aldaflux\GameQuizzBundle\Entity\Game;

#use FOS\CKEditorBundle\Form\Type\CKEditorType;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')->add('soustitre');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
