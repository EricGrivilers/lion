<?php

namespace Caravane\Bundle\EstateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EstateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdOn')
            ->add('updatedOn')
            ->add('prix')
            ->add('oldprix')
            ->add('locfr')
            ->add('locuk')
            ->add('zone')
            ->add('summary')
            ->add('shortdescren')
            ->add('description')
            ->add('descren')
            ->add('sold')
            ->add('ondemand')
            ->add('location')
            ->add('reference')
            ->add('enoption')
            ->add('name')
            ->add('zip')
            ->add('googleMap')
            ->add('moredescrfr')
            ->add('surface')
            ->add('rooms')
            ->add('bathrooms')
            ->add('garages')
            ->add('garden')
            ->add('viewable')
            ->add('status')
            ->add('dayview')
            ->add('weekview')
            ->add('monthview')
            ->add('totalview')
            ->add('lastdayview')
            ->add('lastweekview')
            ->add('lastmonthview')
            ->add('lat')
            ->add('lng')
            ->add('area')
            ->add('category')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Caravane\Bundle\EstateBundle\Entity\Estate'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'caravane_bundle_estatebundle_estate';
    }
}
