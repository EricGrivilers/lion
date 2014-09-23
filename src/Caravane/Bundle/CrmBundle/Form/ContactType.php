<?php

namespace Caravane\Bundle\CrmBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password')
            ->add('email')
            ->add('language')
            ->add('firstname')
            ->add('lastname')
            ->add('tel')
            ->add('zip')
            ->add('fax')
            ->add('street')
            ->add('number')
            ->add('city')
            ->add('country')
            ->add('company')
            ->add('salutation')
            ->add('date')
            ->add('user')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Caravane\Bundle\CrmBundle\Entity\Contact'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'caravane_bundle_crmbundle_contact';
    }
}
