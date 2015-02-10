<?php

namespace Caravane\Bundle\EstateBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Caravane\Bundle\EstateBundle\Repository\AreaRepository;

class SearchType extends AbstractType
{

    protected $prices;
    protected $type;

    protected $location;
    protected $isNewBuilding;


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if(isset($options['prices'])) {
            $this->prices=$options['prices'];
        }
        if(isset($options['type'])) {
            $this->type=$options['type'];
        }
        else {
            $this->type="sale";
        }


        $builder
            ->add('prix','choice', array(
                "required"=>false,
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
                'required' => false,
                "empty_value" => 'Quartier',
                "class"=>"Caravane\Bundle\EstateBundle\Entity\Area",
                'query_builder' => function(AreaRepository $er) {
                    return $er->getAreasQuery();
                },
            ))

            ->add('zone','entity', array(
                "label"=>false,
                "expanded"=>true,
                "multiple"=>true,
                'required' => false,
                "class"=>"Caravane\Bundle\EstateBundle\Entity\Zone",
                "attr"=>array(
                    "class"=>"btn-group btn-group-vertical",
                    "data-toggle"=>"buttons"
                )
            ))
            ->add("rayon","choice",array(
                "required"=>false,
                "label"=>false,
                "empty_value" => 'Rayon',
                "choices"=>array(
                    "0.5"=>"500 m",
                    "1"=>"1 km",
                    "5"=>"5 km",
                    "10"=>"10 km",
                    "20"=>"20 km",
                    "50"=>"50 km"
                )
            ))

            ->add('reference',"text",array(
                "required"=>false,
                "attr"=>array(
                    "placeholder"=>"Reference"
                )
            ))

            ->add('address',"text",array(
                "required"=>false,
                "attr"=>array(
                    "placeholder"=>"Adresse"
                )
            ))

            ->add('category','entity', array(
                "required"=>false,
                "label"=>false,
                "expanded"=>true,
                "multiple"=>true,
                "class"=>"Caravane\Bundle\EstateBundle\Entity\Category",
                "attr"=>array(
                    "class"=>"btn-group btn-group-vertical",
                    "data-toggle"=>"buttons"
                )
            ))
            ->add('location','hidden',array(
                "required"=>false,
            ))

             ->add('isNewBuilding',($this->type!='rent'?'checkbox':'hidden'),array(
                "required"=>false,
                "label"=>"Biens neufs uniquement",
                "data"=>($this->type=='new'?true:false),
                "attr"=>array(
                    "class"=>"btn "
                )
            ))
             ->add('type','hidden',array(
                "required"=>false,
                "data"=>$this->type))
            ->add('keyword','text',array(
                "required"=>false,
                "attr"=>array(
                    "placeholder"=>"Mot clef (ex.: Molière, piscine)"
                )
            ))
            ->add('offset','hidden',array(
                "required"=>false,
                "data"=>0))
            ->add('limit','hidden',array(
                "required"=>false,
                "data"=>24,
            ))
            ->add('latlng','hidden',array(
                "required"=>false,
                "data"=>null,
            ))
            ->add('sort','choice',array(
                "required"=>false,
                "label"=>false,
                //"empty_value" => 'Ordonner les résultats par',
                'preferred_choices' => array('prix asc'),
                "choices"=>array(
                    "prix asc"=>"Prix croissants",
                    "prix desc"=>"Prix decroissants",
                    "locfr asc"=>"Communes",
                    "updatedOn desc"=>"Nouveautés",
                )
            ))
            ->add('around','hidden',array(
                "required"=>false
            ))
            ->add('save','checkbox', array(
                "label"=>"Sauvegarder ma recherche",
                'data' => false,
                "required"=>false
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'prices'=>array(),
            "type"=>"sale",
            "location"=>0,
            "isNewBuilding"=>false
        ));
    }



    /**
     * @return string
     */
    public function getName()
    {
        return 'search_form';
    }
}
