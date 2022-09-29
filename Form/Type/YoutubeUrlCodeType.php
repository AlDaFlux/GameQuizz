<?php

namespace Aldaflux\GameQuizzBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Exception\TransformationFailedException;

use Symfony\Component\Form\CallbackTransformer;


use Symfony\Component\Form\FormBuilderInterface;


class YoutubeUrlCodeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['empty_data' => '','required'=>false]);
    }

    public function getParent()
    {
        return TextType::class;
    }
      
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new CallbackTransformer(
                function ($data) {
                    if ($data)
                    {
                        return("https://www.youtube.com/watch?v=".$data);
                    }
                    else {return("");}
                },
                function ($data) {
                    if ($data)
                    {
                        if (strlen($data)==14)
                        {
                            return($data);
                        }
                        else
                        {
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $data, $match);
                            if (count($match)>1)
                            {
                                return($match[1]);
                            }
                            else
                            {
                                $privateErrorMessage = "L'url n'est pas valide";
                                $publicErrorMessage = "L'url n'est pas valide";
                                $failure = new TransformationFailedException($privateErrorMessage);
//                                $failure->setInvalidMessage($publicErrorMessage );
                                throw $failure;
                            }

                        }
                    }
                    else
                    {
                        return "";
                    }
                }
            ));
          
         
    }

    
    
}