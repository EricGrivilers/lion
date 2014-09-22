<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Type
 *
 * @ORM\Table(name="Type")
 * @ORM\Entity
 */
class Type
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
     * @ORM\Column(name="type_fr", type="string", length=100, nullable=false)
     */
    private $typeFr = '';

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
     * Set typeFr
     *
     * @param string $typeFr
     * @return Type
     */
    public function setTypeFr($typeFr)
    {
        $this->typeFr = $typeFr;

        return $this;
    }

    /**
     * Get typeFr
     *
     * @return string 
     */
    public function getTypeFr()
    {
        return $this->typeFr;
    }

    /**
     * Set typeEn
     *
     * @param string $typeEn
     * @return Type
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
