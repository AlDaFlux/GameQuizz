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
class SoundType extends AbstractType
{
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
/*                'label' => 'Question (Audio) (mp3 /wav )',
*/        
        $resolver->setDefaults([
                'mapped' => false,
                'required' => false,
                'help' => "Audio : mp3 /wav ",
                'constraints' => [
                    new File([
                        'maxSize' => '12m',
                        'mimeTypes' => [
                            'audio/mp3',
                            'audio/x-wav',
                            'audio/mpeg',
                        ],
                        'mimeTypesMessage' => 'Veuillez charger un mp3 ou un .wav',
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
