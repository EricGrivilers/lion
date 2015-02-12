<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slideshow
 *
 * @ORM\Table(name="slideshow")
 * @ORM\Entity
 */
class Slideshow
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
     * @ORM\OneToOne( targetEntity="Caravane\Bundle\EstateBundle\Entity\Estate")
     */
    private $estate;

    /**
     * @ORM\OneToOne( targetEntity="Caravane\Bundle\EstateBundle\Entity\Photo", cascade={"persist"})
     */
    private $photo;

    /**
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;


    /**
     * @var integer
     *
     * @ORM\Column(name="ranking", type="integer", nullable=true)
     */
    private $ranking;




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
     * Set content
     *
     * @param string $content
     * @return Slideshow
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set ranking
     *
     * @param integer $ranking
     * @return Slideshow
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
     * @return Slideshow
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

    /**
     * Set photo
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\Photo $photo
     * @return Slideshow
     */
    public function setPhoto(\Caravane\Bundle\EstateBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \Caravane\Bundle\EstateBundle\Entity\Photo 
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
