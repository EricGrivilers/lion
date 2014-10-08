<?php

namespace Caravane\Bundle\EstateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{

    protected $prices;

    public function __construct($options) {
        $this->prices=$options['prices'];

    }
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('prix','choice', array(
                "label"=>false,
                "expanded"=>true,
                "multiple"=>true,
                /*"choices"=>array(
                    "option1"=>"- de 750.000 €",
                    "option2"=>"de 750.000 à 1.500.000 €",
                    "option3"=>"+ de 750.000 €"
                ),*/
/*"class"=>"Caravane\Bundle\EstateBundle\Entity\Price",*/
                'choices' => $this->prices,
                "attr"=>array(
                    "class"=>"btn-group btn-group-vertical",
                    "data-toggle"=>"buttons"
                )
            ))
            ->add('area',"entity",array(
                "label"=>false,
                "empty_value" => 'Quartier',
                "class"=>"Caravane\Bundle\EstateBundle\Entity\Area"
            ))
            ->add('zone','entity', array(
                "label"=>false,
                "expanded"=>true,
                "multiple"=>true,
                "class"=>"Caravane\Bundle\EstateBundle\Entity\Zone",
                "attr"=>array(
                    "class"=>"btn-group btn-group-vertical",
                    "data-toggle"=>"buttons"
                )
            ))
            ->add('reference',"text",array(
                "attr"=>array(
                    "placeholder"=>"Reference"
                )
            ))
            ->add('category','entity', array(
                "label"=>false,
                "expanded"=>true,
                "multiple"=>true,
                "class"=>"Caravane\Bundle\EstateBundle\Entity\Category",
                "attr"=>array(
                    "class"=>"btn-group btn-group-vertical",
                    "data-toggle"=>"buttons"
                )
            ))
            ->add('location','choice', array(
                "label"=>false,
                "expanded"=>true,
                "multiple"=>false,
                "data"=>0,
                "choices"=>array(
                    "0"=>"Vente",
                    "1"=>"Location"
                ),
                "attr"=>array(
                    "class"=>"btn-group btn-group-justified",
                    "data-toggle"=>"buttons"
                )
            ))
            ->add('isNew','checkbox')
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
        return 'caravane_bundle_estatebundle_search';
    }
}