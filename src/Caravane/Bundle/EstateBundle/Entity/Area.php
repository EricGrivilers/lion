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
 * @ORM\Table(name="Area")
 * @ORM\Entity(repositoryClass="Caravane\Bundle\EstateBundle\Repository\AreaRepository")
 * @ExclusionPolicy("all")
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
    * @Expose
     * @Groups({"list","detail","search"})
     */
    private $nomQuartier;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", nullable=false)
     */
    private $code;


    /**
     * @var string
     *
     * @ORM\Column(name="googlecode", type="text", nullable=true)
     */
    private $googlecode;

    /**
     * @var string
     *
     * @ORM\Column(name="LatLng", type="string", length=32, nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $latlng;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=32, nullable=true)
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lng", type="string", length=32, nullable=true)
     */
    private $lng;

    /**
     * @var integer
     *
     * @ORM\Column(name="zoom", type="integer", nullable=true)
     */
    private $zoom;

    /**
     * @ORM\OneToMany( targetEntity="Caravane\Bundle\EstateBundle\Entity\Estate", mappedBy="area")
     */
    private $estate;



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

    /**
     * Set lat
     *
     * @param string $lat
     * @return Area
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return Area
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->estate = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Area
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Add estate
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\Estate $estate
     * @return Area
     */
    public function addEstate(\Caravane\Bundle\EstateBundle\Entity\Estate $estate)
    {
        $this->estate[] = $estate;

        return $this;
    }

    /**
     * Remove estate
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\Estate $estate
     */
    public function removeEstate(\Caravane\Bundle\EstateBundle\Entity\Estate $estate)
    {
        $this->estate->removeElement($estate);
    }

    /**
     * Get estate
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstate()
    {
        return $this->estate;
    }
}
