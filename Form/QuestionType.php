<?php

namespace Aldaflux\GameQuizzBundle\Form;

use Aldaflux\GameQuizzBundle\Entity\Question;


use Symfony\Component\Form\AbstractType;

use Aldaflux\GameQuizzBundle\Form\Type\SoundType;
use Aldaflux\GameQuizzBundle\Form\Type\VideoType;


use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;




class QuestionType extends AbstractType
{
     
    private $youtube;
    private $mpg;
    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        
        $builder->add('name');
        $builder->add('ordre');
        $builder->add('questionText');
        
        $builder->add('questionAudioFichier', SoundType::class);
         
        if ($options["fields"]["youtube"])
        {
            $builder->add('questionVideoYoutube');
        }
        if ($options["fields"]["mpg"])
        {
            $builder->add('questionVideoFichier', VideoType::class);
        }
        $builder->add('answerText');

        $builder->add('answerAudioFichier', SoundType::class);

        if ($options["fields"]["mpg"])
        {
            $builder->add('answerVideoFichier', VideoType::class);
        }
        if ($options["fields"]["youtube"])
        {
            $builder->add('answerVideoYoutube');
        }
        
        $builder->add('published');
        $builder->add('answerPlusText');

        $builder->add('answerPlusAudioFichier', SoundType::class);

    }

    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
            'fields' => [],
        ]);
    }
}
