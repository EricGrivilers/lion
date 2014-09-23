<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;

/**
 * Category
 *
 * @ORM\Table(name="Category")
 * @ORM\Entity
 */
class Category
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
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="type_en", type="string", length=100, nullable=false)
     */
    private $typeEn = '';





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
}
