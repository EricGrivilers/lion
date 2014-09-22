<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slideshow
 *
 * @ORM\Table(name="slideshow", uniqueConstraints={@ORM\UniqueConstraint(name="ranking", columns={"ranking"}), @ORM\UniqueConstraint(name="item_id", columns={"item_id"})})
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
     * @var integer
     *
     * @ORM\Column(name="item_id", type="integer", nullable=false)
     */
    private $itemId;

    /**
     * @var integer
     *
     * @ORM\Column(name="ranking", type="integer", nullable=false)
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
     * Set itemId
     *
     * @param integer $itemId
     * @return Slideshow
     */
    public function setItemId($itemId)
    {
        $this->itemId = $itemId;

        return $this;
    }

    /**
     * Get itemId
     *
     * @return integer 
     */
    public function getItemId()
    {
        return $this->itemId;
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
}
