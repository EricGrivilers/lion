<?php

namespace Caravane\Bundle\UserBundle\Entity;


use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\PreSerialize;


use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ExclusionPolicy("all")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
    * @ORM\OneToOne(targetEntity="Caravane\Bundle\CrmBundle\Entity\Contact" ,  mappedBy="user", cascade={"persist"} )
    * @Expose
    * @Groups({"list","detail","search"})
    */
    protected $contact;



     /**
     * @var integer
     *
     * @ORM\OneToMany( targetEntity="Caravane\Bundle\EstateBundle\Entity\UserEstate", mappedBy="user")
     * @Expose
     * @Groups({"list","detail","search"})
     */
    protected $estate;


    public function __construct()
    {
        parent::__construct();
        // your own logic
    }



    public function setEmail($email)
    {
             parent::setEmail($email);
             $this->setUsername($email);
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
     * Set contact
     *
     * @param \Caravane\Bundle\CrmBundle\Entity\Contact $contact
     * @return User
     */
    public function setContact(\Caravane\Bundle\CrmBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \Caravane\Bundle\CrmBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Add estate
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\UserEstate $estate
     * @return User
     */
    public function addEstate(\Caravane\Bundle\EstateBundle\Entity\UserEstate $estate)
    {
        $this->estate[] = $estate;

        return $this;
    }

    /**
     * Remove estate
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\UserEstate $estate
     */
    public function removeEstate(\Caravane\Bundle\EstateBundle\Entity\UserEstate $estate)
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




    public function hasEstate($estate) {
        $estates=$this->getEstate();
        foreach($estates as $es) {
            if($es->getEstate()->getId()==$estate->getId() && $es->getSaved()) {
                return true;
            }
        }
        return false;
    }
}
