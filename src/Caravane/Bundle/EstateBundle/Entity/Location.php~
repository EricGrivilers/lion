<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table(name="Location")
 * @ORM\Entity
 */
class Location
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
     * @ORM\Column(name="zip", type="string", length=4, nullable=false)
     */
    private $zip = '';

    /**
     * @var string
     *
     * @ORM\Column(name="fr", type="string", length=100, nullable=false)
     */
    private $fr = '';

    /**
     * @var string
     *
     * @ORM\Column(name="nl", type="string", length=100, nullable=false)
     */
    private $nl = '';

    /**
     * @var string
     *
     * @ORM\Column(name="innercity", type="string", length=10, nullable=false)
     */
    private $innercity = 'true';

    /**
     * @var string
     *
     * @ORM\Column(name="fixed", type="string", length=5, nullable=false)
     */
    private $fixed;



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
     * Set zip
     *
     * @param string $zip
     * @return Location
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string 
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set fr
     *
     * @param string $fr
     * @return Location
     */
    public function setFr($fr)
    {
        $this->fr = $fr;

        return $this;
    }

    /**
     * Get fr
     *
     * @return string 
     */
    public function getFr()
    {
        return $this->fr;
    }

    /**
     * Set nl
     *
     * @param string $nl
     * @return Location
     */
    public function setNl($nl)
    {
        $this->nl = $nl;

        return $this;
    }

    /**
     * Get nl
     *
     * @return string 
     */
    public function getNl()
    {
        return $this->nl;
    }

    /**
     * Set innercity
     *
     * @param string $innercity
     * @return Location
     */
    public function setInnercity($innercity)
    {
        $this->innercity = $innercity;

        return $this;
    }

    /**
     * Get innercity
     *
     * @return string 
     */
    public function getInnercity()
    {
        return $this->innercity;
    }

    /**
     * Set fixed
     *
     * @param string $fixed
     * @return Location
     */
    public function setFixed($fixed)
    {
        $this->fixed = $fixed;

        return $this;
    }

    /**
     * Get fixed
     *
     * @return string 
     */
    public function getFixed()
    {
        return $this->fixed;
    }
}
