<?php

namespace Caravane\Bundle\EstateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Caravane\Bundle\EstateBundle\Repository\LocationRepository;

class EstateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        //    ->add('createdOn')
         //   ->add('updatedOn')
        ->add('status','checkbox')
        ->add('reference')
            ->add('prix')
          //  ->add('oldprix')
            ->add('locfr')
            ->add('locfr', 'entity', array(
                 "class"=>"Caravane\Bundle\EstateBundle\Entity\Location"
                ))
        //    ->add('locuk')
            ->add('zone')
        //    ->add('summary','textarea')
            //->add('shortdescren')
            ->add('description','ckeditor')
       //     ->add('descren')
        //    ->add('sold')
            ->add('ondemand')
            ->add('location','checkbox')
            ->add('reference')
        //    ->add('enoption','checkbox')
            ->add('name')
            ->add('zip')
        //    ->add('googleMap')
        //    ->add('moredescrfr')
            ->add('surface')
            ->add('rooms')
            ->add('bathrooms')
            ->add('garages')
            ->add('garden','choice',array(
                'required'    => false,
                "choices"=>array("Jardin"=>"Jardin","Terrasse"=>"Terrasse")
            ))
        //    ->add('viewable')
            
      //      ->add('dayview')
       //     ->add('weekview')
       //     ->add('monthview')
       //     ->add('totalview')
       //     ->add('lastdayview')
       //     ->add('lastweekview')
       //     ->add('lastmonthview')
            ->add('lat')
            ->add('lng')
             ->add('area','entity',array(
                'label'=>"Quartier",
                "class"=>"Caravane\Bundle\EstateBundle\Entity\Area"
            ))
            ->add('category','entity',array(
                'label'=>"Type",
                "class"=>"Caravane\Bundle\EstateBundle\Entity\Category"
            ))
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
