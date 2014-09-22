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
     * @ORM\Column(name="estate_id", type="integer", nullable=true)
     */
    private $estateId;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=false)
     */
    private $photo;

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
     * Set estateId
     *
     * @param integer $estateId
     * @return Photo
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

    /**
     * Set photo
     *
     * @param string $photo
     * @return Photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
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
}
