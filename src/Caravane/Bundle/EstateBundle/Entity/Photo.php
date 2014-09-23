<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Photo
 *
 * @ORM\Table(name="Photo", uniqueConstraints={@ORM\UniqueConstraint(name="item_id", columns={"estate_id", "photo"})})
 * @ORM\Entity
 */
class Photo
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
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Caravane\Bundle\EstateBundle\Entity\Estate", inversedBy="photo")
     */
    private $estate;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * @var integer
     *
     * @ORM\Column(name="ranking", type="integer", nullable=false)
     */
    private $ranking;


    public function __toString() {
        return $this->filename;
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
     * Set filename
     *
     * @param string $filename
     * @return Photo
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set ranking
     *
     * @param integer $ranking
     * @return Photo
     */
    public function setRanking($ranking)
    {
        $this->ranking = $ranking;

        return $this;
    }

    /**
     * Get ranking
     *
     * @return integer
     */
    public function getRanking()
    {
        return $this->ranking;
    }

    /**
     * Set estate
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\Estate $estate
     * @return Photo
     */
    public function setEstate(\Caravane\Bundle\EstateBundle\Entity\Estate $estate = null)
    {
        $this->estate = $estate;

        return $this;
    }

    /**
     * Get estate
     *
     * @return \Caravane\Bundle\EstateBundle\Entity\Estate
     */
    public function getEstate()
    {
        return $this->estate;
    }
}
