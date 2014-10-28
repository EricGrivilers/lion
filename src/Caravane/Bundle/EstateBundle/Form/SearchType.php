<?php

namespace Caravane\Bundle\EstateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SearchType extends AbstractType
{

    protected $prices;


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(isset($options['prices'])) {
            $this->prices=$options['prices'];
        }


        $builder
            ->add('prix','choice', array(
                "label"=>false,
                "expanded"=>true,
                "multiple"=>true,
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
            ->add('location','hidden')

            ->add('isNewBuilding','checkbox',array(
                "label"=>"Biens neufs uniquement",
                "attr"=>array(
                    "class"=>"btn "
                )
            ))
            ->add('keyword','text',array(
                "attr"=>array(
                    "placeholder"=>"Mot clef (ex.: piscine, brugmann)"
                )
            ))
            ->add('offset','hidden',array(
                "data"=>0))
            ->add('limit','hidden',array(
                "data"=>24,
            ))
            ->add('sort','choice',array(
                "label"=>false,
                "empty_value" => 'Ordonner les résultats par',
                "choices"=>array(
                    "prix asc"=>"Prix croissants",
                    "prix desc"=>"Prix decroissants",
                    "locfr asc"=>"Communes",
                    "updatedOn desc"=>"Nouveautés",
                )
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array('prices'=>array()));
    }



    /**
     * @return string
     */
    public function getName()
    {
        return 'search_form';
    }
}
