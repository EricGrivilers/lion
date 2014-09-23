<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZipEstate
 *
 * @ORM\Table(name="zip_estate")
 * @ORM\Entity
 */
class ZipEstate
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
     * @ORM\Column(name="locationZip", type="string", length=10, nullable=false)
     */
    private $locationzip = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="estate_id", type="integer", nullable=false)
     */
    private $estateId = '0';



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
     * Set locationzip
     *
     * @param string $locationzip
     * @return ZipEstate
     */
    public function setLocationzip($locationzip)
    {
        $this->locationzip = $locationzip;

        return $this;
    }

    /**
     * Get locationzip
     *
     * @return string 
     */
    public function getLocationzip()
    {
        return $this->locationzip;
    }

    /**
     * Set estateId
     *
     * @param integer $estateId
     * @return ZipEstate
     */
    public function setEstateId($estateId)
    {
        $this->estateId = $estateId;

        return $this;
    }

    /**
     * Get estateId
     *
     * @return integer 
     */
    public function getEstateId()
    {
        return $this->estateId;
    }
}
