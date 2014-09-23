<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statistique
 *
 * @ORM\Table(name="Statistique", indexes={@ORM\Index(name="itemId", columns={"estate_id"})})
 * @ORM\Entity
 */
class Statistique
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
     * @ORM\Column(name="days", type="text", nullable=false)
     */
    private $days;

    /**
     * @var string
     *
     * @ORM\Column(name="months", type="text", nullable=false)
     */
    private $months;

    /**
     * @var string
     *
     * @ORM\Column(name="weeks", type="text", nullable=false)
     */
    private $weeks;

    /**
     * @var string
     *
     * @ORM\Column(name="years", type="text", nullable=false)
     */
    private $years;

    /**
     * @var string
     *
     * @ORM\Column(name="wdays", type="text", nullable=false)
     */
    private $wdays;



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
     * @return Statistique
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
     * Set days
     *
     * @param string $days
     * @return Statistique
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return string 
     */
    public function getDays()
    {
        return $this->days;
    }

    /**
     * Set months
     *
     * @param string $months
     * @return Statistique
     */
    public function setMonths($months)
    {
        $this->months = $months;

        return $this;
    }

    /**
     * Get months
     *
     * @return string 
     */
    public function getMonths()
    {
        return $this->months;
    }

    /**
     * Set weeks
     *
     * @param string $weeks
     * @return Statistique
     */
    public function setWeeks($weeks)
    {
        $this->weeks = $weeks;

        return $this;
    }

    /**
     * Get weeks
     *
     * @return string 
     */
    public function getWeeks()
    {
        return $this->weeks;
    }

    /**
     * Set years
     *
     * @param string $years
     * @return Statistique
     */
    public function setYears($years)
    {
        $this->years = $years;

        return $this;
    }

    /**
     * Get years
     *
     * @return string 
     */
    public function getYears()
    {
        return $this->years;
    }

    /**
     * Set wdays
     *
     * @param string $wdays
     * @return Statistique
     */
    public function setWdays($wdays)
    {
        $this->wdays = $wdays;

        return $this;
    }

    /**
     * Get wdays
     *
     * @return string 
     */
    public function getWdays()
    {
        return $this->wdays;
    }
}
