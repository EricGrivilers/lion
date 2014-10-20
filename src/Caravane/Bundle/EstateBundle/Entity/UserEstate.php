<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserEstate
 *
 * @ORM\Table(name="UserEstate")
 * @ORM\Entity
 */
class UserEstate
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
     * @ORM\ManyToOne( targetEntity="Caravane\Bundle\UserBundle\Entity\User", inversedBy="estate")
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\ManyToOne( targetEntity="Caravane\Bundle\EstateBundle\Entity\Estate", inversedBy="user")
     */
    private $estate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="counter", type="integer", length=11, nullable=true)
     */
    private $counter;

    /**
     * @var integer
     *
     * @ORM\Column(name="saved", type="boolean", nullable=true)
     */
    private $saved;

   



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
     * Set date
     *
     * @param \DateTime $date
     * @return UserEstate
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set counter
     *
     * @param integer $counter
     * @return UserEstate
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;

        return $this;
    }

    /**
     * Get counter
     *
     * @return integer 
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * Set saved
     *
     * @param boolean $saved
     * @return UserEstate
     */
    public function setSaved($saved)
    {
        $this->saved = $saved;

        return $this;
    }

    /**
     * Get saved
     *
     * @return boolean 
     */
    public function getSaved()
    {
        return $this->saved;
    }

    /**
     * Set user
     *
     * @param \Caravane\Bundle\UserBundle\Entity\User $user
     * @return UserEstate
     */
    public function setUser(\Caravane\Bundle\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Caravane\Bundle\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set estate
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\Estate $estate
     * @return UserEstate
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
