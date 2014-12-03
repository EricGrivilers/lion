<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\PreSerialize;



/**
 * Area
 *
 * @ORM\Table(name="Area", uniqueConstraints={@ORM\UniqueConstraint(name="nom", columns={"nom_quartier"})})
 * @ORM\Entity
 */
class Area
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
     * @ORM\Column(name="nom_quartier", type="string", length=255, nullable=false)
     */
    private $nomQuartier;

    /**
     * @var string
     *
     * @ORM\Column(name="googlecode", type="text", nullable=false)
     */
    private $googlecode;

    /**
     * @var string
     *
     * @ORM\Column(name="LatLng", type="string", length=32, nullable=false)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $latlng;

    /**
     * @var integer
     *
     * @ORM\Column(name="zoom", type="integer", nullable=false)
     */
    private $zoom;



    public function __toString() {
        return $this->nomQuartier;
    }


    public function getName()
    {
        return $this->getNomQuartier();
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
     * Set nomQuartier
     *
     * @param string $nomQuartier
     * @return Area
     */
    public function setNomQuartier($nomQuartier)
    {
        $this->nomQuartier = $nomQuartier;

        return $this;
    }

    /**
     * Get nomQuartier
     *
     * @return string 
     */
    public function getNomQuartier()
    {
        return $this->nomQuartier;
    }

    /**
     * Set googlecode
     *
     * @param string $googlecode
     * @return Area
     */
    public function setGooglecode($googlecode)
    {
        $this->googlecode = $googlecode;

        return $this;
    }

    /**
     * Get googlecode
     *
     * @return string 
     */
    public function getGooglecode()
    {
        return $this->googlecode;
    }

    /**
     * Set latlng
     *
     * @param string $latlng
     * @return Area
     */
    public function setLatlng($latlng)
    {
        $this->latlng = $latlng;

        return $this;
    }

    /**
     * Get latlng
     *
     * @return string 
     */
    public function getLatlng()
    {
        return $this->latlng;
    }

    /**
     * Set zoom
     *
     * @param integer $zoom
     * @return Area
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;

        return $this;
    }

    /**
     * Get zoom
     *
     * @return integer 
     */
    public function getZoom()
    {
        return $this->zoom;
    }
}
