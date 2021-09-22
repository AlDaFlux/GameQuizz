<?php

namespace Aldaflux\GameQuizzBundle\Form;

use Aldaflux\GameQuizzBundle\Entity\Answer;

use Aldaflux\GameQuizzBundle\Form\Type\SoundType;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ordre');
        $builder->add('answerText');
        $builder->add('answerAudioFichier', SoundType::class);
        $builder->add('isGood');
    }
    
    

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
        ]);
    }
}
