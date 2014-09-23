<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Search
 *
 * @ORM\Table(name="Search", uniqueConstraints={@ORM\UniqueConstraint(name="userId", columns={"user_id"})})
 * @ORM\Entity
 */
class Search
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=3, nullable=false)
     */
    private $type = '';

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=100, nullable=false)
     */
    private $price = '';

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=100, nullable=false)
     */
    private $location = '';

    /**
     * @var string
     *
     * @ORM\Column(name="searchfor", type="string", length=100, nullable=false)
     */
    private $searchfor = '';

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_possessiontypes", type="string", length=255, nullable=false)
     */
    private $mobilePossessiontypes;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile_zipcodes", type="string", length=255, nullable=false)
     */
    private $mobileZipcodes;



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
     * @return Search
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
     * Set type
     *
     * @param string $type
     * @return Search
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set price
     *
     * @param string $price
     * @return Search
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Search
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set searchfor
     *
     * @param string $searchfor
     * @return Search
     */
    public function setSearchfor($searchfor)
    {
        $this->searchfor = $searchfor;

        return $this;
    }

    /**
     * Get searchfor
     *
     * @return string 
     */
    public function getSearchfor()
    {
        return $this->searchfor;
    }

    /**
     * Set mobilePossessiontypes
     *
     * @param string $mobilePossessiontypes
     * @return Search
     */
    public function setMobilePossessiontypes($mobilePossessiontypes)
    {
        $this->mobilePossessiontypes = $mobilePossessiontypes;

        return $this;
    }

    /**
     * Get mobilePossessiontypes
     *
     * @return string 
     */
    public function getMobilePossessiontypes()
    {
        return $this->mobilePossessiontypes;
    }

    /**
     * Set mobileZipcodes
     *
     * @param string $mobileZipcodes
     * @return Search
     */
    public function setMobileZipcodes($mobileZipcodes)
    {
        $this->mobileZipcodes = $mobileZipcodes;

        return $this;
    }

    /**
     * Get mobileZipcodes
     *
     * @return string 
     */
    public function getMobileZipcodes()
    {
        return $this->mobileZipcodes;
    }
}
