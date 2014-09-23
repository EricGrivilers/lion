<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserEstate
 *
 * @ORM\Table(name="user_estate")
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
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="estate_id", type="integer", nullable=true)
     */
    private $estateId = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date = '0000-00-00 00:00:00';

    /**
     * @var integer
     *
     * @ORM\Column(name="counter", type="integer", nullable=false)
     */
    private $counter;

    /**
     * @var integer
     *
     * @ORM\Column(name="saved", type="integer", nullable=false)
     */
    private $saved;

    /**
     * @var boolean
     *
     * @ORM\Column(name="view", type="boolean", nullable=false)
     */
    private $view;



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
     * Set userId
     *
     * @param integer $userId
     * @return UserEstate
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set estateId
     *
     * @param integer $estateId
     * @return UserEstate
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
     * @param integer $saved
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
     * @return integer 
     */
    public function getSaved()
    {
        return $this->saved;
    }

    /**
     * Set view
     *
     * @param boolean $view
     * @return UserEstate
     */
    public function setView($view)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * Get view
     *
     * @return boolean 
     */
    public function getView()
    {
        return $this->view;
    }
}
