<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\PreSerialize;


use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Category
 *
 * @ORM\Table(name="Category")
 * @ORM\Entity
 */
class Category implements Translatable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     * @Gedmo\Translatable
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="type_en", type="string", length=100, nullable=false)
     */
    private $typeEn = '';

    /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;


    public function __toString() {
        return $this->name;
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set typeEn
     *
     * @param string $typeEn
     * @return Category
     */
    public function setTypeEn($typeEn)
    {
        $this->typeEn = $typeEn;

        return $this;
    }

    /**
     * Get typeEn
     *
     * @return string 
     */
    public function getTypeEn()
    {
        return $this->typeEn;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
}
