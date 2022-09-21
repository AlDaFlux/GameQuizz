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
            $builder->add('questionVideoFichier', VideoType::class, ["label"=>"Vidéo de mise en situation "]);
        }
        if ($options["fields"]["videolink"])
        {
            $builder->add('questionVideoLink',null,['attr'=>['placeholder'=>'https://storage.gra1.cloud.ovh.net/v1/AUTH_b88187e7335244f6a9912624de435103/videos2017/logo/mp4/fr/720/010103_720p_fr.mp4']]);
        }
        
        $builder->add('answerText');

        $builder->add('answerAudioFichier', SoundType::class);

        if ($options["fields"]["mpg"])
        {
            $builder->add('answerVideoFichier', VideoType::class, ["label"=>"Vidéo accompagnant la réponse "]);
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
