<?php

namespace Caravane\Bundle\EstateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SlideshowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('content')
            //->add('ranking')
           // ->add('estate')
            ->add('photo', new PhotoType('photos/slide'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Caravane\Bundle\EstateBundle\Entity\Slideshow'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'caravane_bundle_estatebundle_slideshow';
    }
}
