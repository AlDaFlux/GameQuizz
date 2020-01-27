<?php

namespace Aldaflux\GameQuizzBundle\Form;

use Aldaflux\GameQuizzBundle\Entity\Question;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('ordre');
        $builder->add('questionText');
        $builder->add('questionAudio');
        $builder->add('questionVideo');
        $builder->add('questionVideoYoutube');
        $builder->add('answerText');
        $builder->add('answerAudio');
        $builder->add('answerVideo');
        $builder->add('answerVideoYoutube');
        $builder->add('published');
        $builder->add('answerPlusText');
        $builder->add('answerPlusAudio');
    }

    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
