<?php
 
namespace Aldaflux\GameQuizzBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;




/**
 * Defines the custom form field type used to manipulate datetime values across
 * Bootstrap Date\Time Picker javascript plugin.
 *
 * See https://symfony.com/doc/current/cookbook/form/create_custom_field_type.html
 *
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class VideoType extends AbstractType
{
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
                'mapped' => false,
                'help' => "Vidéo  : mpg ou mp4",
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '100m',
                        'mimeTypes' => [
                            'video/mp4',
                        ],
                        'mimeTypesMessage' => 'Veuillez charger un mp4 ou mpg ou un .wav',
                    ])
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FileType::class;
    }
}
