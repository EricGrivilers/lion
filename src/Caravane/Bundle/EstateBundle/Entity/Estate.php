<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\PreSerialize;


use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;


/**
 * Estate
 *
 * @ORM\Table(name="Estate")
 * @ORM\Entity(repositoryClass="Caravane\Bundle\EstateBundle\Repository\EstateRepository")
 * @ExclusionPolicy("all")
 *
 */
class Estate
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdOn", type="datetime", nullable=true)
     */
    private $createdOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedOn", type="datetime", nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $updatedOn;

    /**
     * @ORM\OneToMany( targetEntity="Caravane\Bundle\EstateBundle\Entity\Photo", mappedBy="estate")
     * @ORM\OrderBy({"ranking" = "ASC"})
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $photo;


    /**
    * @Groups({"list","detail","search"})
    * @Expose
    */
    private $defaultPict;


    private $photos;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=8, scale=0, nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="oldPrix", type="decimal", precision=8, scale=0, nullable=true)
     */
    private $oldprix;

    /**
     * @var string
     *
     * @ORM\Column(name="locfr", type="string", length=50, nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $locfr;

    /**
     * @var string
     *
     * @ORM\Column(name="locuk", type="string", length=50, nullable=true)
     */
    private $locuk;

    /**
     * @ORM\ManyToOne( targetEntity="Caravane\Bundle\EstateBundle\Entity\Area", inversedBy="estate")
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $area;

    /**
     * @var integer
     *
     * @ORM\ManyToOne( targetEntity="Caravane\Bundle\EstateBundle\Entity\Zone")
     */
    private $zone;

    /**
     * @var string
     *
     * @ORM\Column(name="summary", type="text", nullable=false)
     * @Gedmo\Translatable
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $summary;

    /**
     * @var string
     *
     * @ORM\Column(name="shortdescren", type="string", length=255, nullable=true)
     */
    private $shortdescren;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Gedmo\Translatable
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="descren", type="text", nullable=true)
     */
    private $descren;

    /**
     * @var string
     *
     * @ORM\Column(name="sold", type="boolean", nullable=true)
     */
    private $sold;

    /**
     * @var string
     *
     * @ORM\Column(name="ondemand", type="boolean", nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $ondemand;


    /**
     * @var string
     *
     * @ORM\Column(name="location", type="boolean", nullable=true)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=20, nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="enoption", type="boolean", nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $enoption;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name = '';

    /**
     * @var integer
     *
     * @ORM\ManyToOne( targetEntity="Caravane\Bundle\EstateBundle\Entity\Category")
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $category;

    /**
     * @var integer
     *
     * @ORM\Column(name="zip", type="integer", nullable=false)
     */
    private $zip = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="google_map", type="text", nullable=true)
     */
    private $googleMap;

    /**
     * @var string
     *
     * @ORM\Column(name="moredescrfr", type="text", nullable=true)
     */
    private $moredescrfr;

   /**
     * @var integer
     *
     * @ORM\Column(name="surface", type="decimal", nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $surface = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="rooms", type="integer", nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $rooms = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="bathrooms", type="integer", nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $bathrooms = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="garages", type="integer", nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $garages = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="garden", type="string", length=10, nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $garden = '';

    /**
     * @var string
     *
     * @ORM\Column(name="viewable", type="string", length=10, nullable=true)
     */
    private $viewable = '';

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status = true;


     /**
     * @var string
     *
     * @ORM\Column(name="isNewBuilding", type="boolean", nullable=true)
     * @Expose
     * @Groups({"list","detail","search"})
     */
    private $isNewBuilding = false;


    /**
     * @var integer
     *
     * @ORM\Column(name="dayview", type="integer", nullable=true)
     */
    private $dayview;

    /**
     * @var integer
     *
     * @ORM\Column(name="weekview", type="integer", nullable=true)
     */
    private $weekview;

    /**
     * @var integer
     *
     * @ORM\Column(name="monthview", type="integer", nullable=true)
     */
    private $monthview;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalview", type="integer", nullable=true)
     */
    private $totalview;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastdayview", type="integer", nullable=true)
     */
    private $lastdayview;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastweekview", type="integer", nullable=true)
     */
    private $lastweekview;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastmonthview", type="integer", nullable=true)
     */
    private $lastmonthview;

    /**
     * @var string
     *
     * @ORM\Column(name="Lat", type="string", length=50, nullable=true)
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="Lng", type="string", length=50, nullable=true)
     */
    private $lng;


    private $shortReference;

    private $isNew;

    private $isUpdated;

    private $distance;

     /**
     * @var integer
     *
     * @ORM\OneToMany( targetEntity="Caravane\Bundle\EstateBundle\Entity\UserEstate", mappedBy="estate")
     */
    private $user;

    public function setDistance($distance) {
        $this->distance = $distance;
        return $this;
    }

    public function getDistance() {
        return $this->distance;
    }


    public function getShortReference() {
        return str_replace("030/",'',$this->reference);
    }

    /**
     * Get public
     *
     * @return string
     */
    public function getPublic()
    {
        return ($this->status?'checked':'');
    }


    /**
     * Get actif
     *
     * @return string
     */
    public function getActif()
    {
        return ($this->status?'Y':'');
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photo = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set createdOn
     *
     * @param \DateTime $createdOn
     * @return Estate
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Set updatedOn
     *
     * @param \DateTime $updatedOn
     * @return Estate
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    /**
     * Get updatedOn
     *
     * @return \DateTime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }

    /**
     * Set prix
     *
     * @param string $prix
     * @return Estate
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     * @PreSerialize
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set oldprix
     *
     * @param string $oldprix
     * @return Estate
     */
    public function setOldprix($oldprix)
    {
        $this->oldprix = $oldprix;

        return $this;
    }

    /**
     * Get oldprix
     *
     * @return string
     */
    public function getOldprix()
    {
        return $this->oldprix;
    }

    /**
     * Set locfr
     *
     * @param string $locfr
     * @return Estate
     */
    public function setLocfr($locfr)
    {
        $this->locfr = $locfr;

        return $this;
    }

    /**
     * Get locfr
     *
     * @return string
     */
    public function getLocfr()
    {
        return $this->locfr;
    }

    /**
     * Set locuk
     *
     * @param string $locuk
     * @return Estate
     */
    public function setLocuk($locuk)
    {
        $this->locuk = $locuk;

        return $this;
    }

    /**
     * Get locuk
     *
     * @return string
     */
    public function getLocuk()
    {
        return $this->locuk;
    }



    /**
     * Set summary
     *
     * @param string $summary
     * @return Estate
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * Get summary
     *
     * @return string
     */
    public function getSummary()
    {
        if(!$this->summary) {
            if(!$this->description) {
                return $this->summary;
            }
            $i=0;
            $tA=explode(".",strip_tags($this->description));
            if(is_array($tA)) {
                while(strlen($this->summary)<150) {
                    $this->summary.=$tA[$i].". ";
                    $i++;
                }
            }

        }

        return $this->summary;
    }

    /**
     * Set shortdescren
     *
     * @param string $shortdescren
     * @return Estate
     */
    public function setShortdescren($shortdescren)
    {
        $this->shortdescren = $shortdescren;

        return $this;
    }

    /**
     * Get shortdescren
     *
     * @return string
     */
    public function getShortdescren()
    {
        return $this->shortdescren;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Estate
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set descren
     *
     * @param string $descren
     * @return Estate
     */
    public function setDescren($descren)
    {
        $this->descren = $descren;

        return $this;
    }

    /**
     * Get descren
     *
     * @return string
     */
    public function getDescren()
    {
        return $this->descren;
    }

    /**
     * Set sold
     *
     * @param boolean $sold
     * @return Estate
     */
    public function setSold($sold)
    {
        $this->sold = $sold;

        return $this;
    }

    /**
     * Get sold
     *
     * @return boolean
     */
    public function getSold()
    {
        return $this->sold;
    }

    /**
     * Set ondemand
     *
     * @param boolean $ondemand
     * @return Estate
     */
    public function setOndemand($ondemand)
    {
        $this->ondemand = $ondemand;

        return $this;
    }

    /**
     * Get ondemand
     *
     * @return boolean
     */
    public function getOndemand()
    {
        return $this->ondemand;
    }

    /**
     * Set location
     *
     * @param string $location
     * @return Estate
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
     * Set reference
     *
     * @param string $reference
     * @return Estate
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set enoption
     *
     * @param string $enoption
     * @return Estate
     */
    public function setEnoption($enoption)
    {
        $this->enoption = $enoption;

        return $this;
    }

    /**
     * Get enoption
     *
     * @return string
     */
    public function getEnoption()
    {
        return $this->enoption;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Estate
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set zip
     *
     * @param integer $zip
     * @return Estate
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return integer
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set googleMap
     *
     * @param string $googleMap
     * @return Estate
     */
    public function setGoogleMap($googleMap)
    {
        $this->googleMap = $googleMap;

        return $this;
    }

    /**
     * Get googleMap
     *
     * @return string
     */
    public function getGoogleMap()
    {
        return $this->googleMap;
    }

    /**
     * Set moredescrfr
     *
     * @param string $moredescrfr
     * @return Estate
     */
    public function setMoredescrfr($moredescrfr)
    {
        $this->moredescrfr = $moredescrfr;

        return $this;
    }

    /**
     * Get moredescrfr
     *
     * @return string
     */
    public function getMoredescrfr()
    {
        return $this->moredescrfr;
    }

    /**
     * Set surface
     *
     * @param string $surface
     * @return Estate
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;

        return $this;
    }

    /**
     * Get surface
     *
     * @return string
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * Set rooms
     *
     * @param integer $rooms
     * @return Estate
     */
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;

        return $this;
    }

    /**
     * Get rooms
     *
     * @return integer
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set bathrooms
     *
     * @param integer $bathrooms
     * @return Estate
     */
    public function setBathrooms($bathrooms)
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    /**
     * Get bathrooms
     *
     * @return integer
     */
    public function getBathrooms()
    {
        return $this->bathrooms;
    }

    /**
     * Set garages
     *
     * @param integer $garages
     * @return Estate
     */
    public function setGarages($garages)
    {
        $this->garages = $garages;

        return $this;
    }

    /**
     * Get garages
     *
     * @return integer
     */
    public function getGarages()
    {
        return $this->garages;
    }

    /**
     * Set garden
     *
     * @param string $garden
     * @return Estate
     */
    public function setGarden($garden)
    {
        $this->garden = $garden;

        return $this;
    }

    /**
     * Get garden
     *
     * @return string
     */
    public function getGarden()
    {
        return $this->garden;
    }

    /**
     * Set viewable
     *
     * @param string $viewable
     * @return Estate
     */
    public function setViewable($viewable)
    {
        $this->viewable = $viewable;

        return $this;
    }

    /**
     * Get viewable
     *
     * @return string
     */
    public function getViewable()
    {
        return $this->viewable;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Estate
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }




    /**
     * Set dayview
     *
     * @param integer $dayview
     * @return Estate
     */
    public function setDayview($dayview)
    {
        $this->dayview = $dayview;

        return $this;
    }

    /**
     * Get dayview
     *
     * @return integer
     */
    public function getDayview()
    {
        return $this->dayview;
    }

    /**
     * Set weekview
     *
     * @param integer $weekview
     * @return Estate
     */
    public function setWeekview($weekview)
    {
        $this->weekview = $weekview;

        return $this;
    }

    /**
     * Get weekview
     *
     * @return integer
     */
    public function getWeekview()
    {
        return $this->weekview;
    }

    /**
     * Set monthview
     *
     * @param integer $monthview
     * @return Estate
     */
    public function setMonthview($monthview)
    {
        $this->monthview = $monthview;

        return $this;
    }

    /**
     * Get monthview
     *
     * @return integer
     */
    public function getMonthview()
    {
        return $this->monthview;
    }

    /**
     * Set totalview
     *
     * @param integer $totalview
     * @return Estate
     */
    public function setTotalview($totalview)
    {
        $this->totalview = $totalview;

        return $this;
    }

    /**
     * Get totalview
     *
     * @return integer
     */
    public function getTotalview()
    {
        return $this->totalview;
    }

    /**
     * Set lastdayview
     *
     * @param integer $lastdayview
     * @return Estate
     */
    public function setLastdayview($lastdayview)
    {
        $this->lastdayview = $lastdayview;

        return $this;
    }

    /**
     * Get lastdayview
     *
     * @return integer
     */
    public function getLastdayview()
    {
        return $this->lastdayview;
    }

    /**
     * Set lastweekview
     *
     * @param integer $lastweekview
     * @return Estate
     */
    public function setLastweekview($lastweekview)
    {
        $this->lastweekview = $lastweekview;

        return $this;
    }

    /**
     * Get lastweekview
     *
     * @return integer
     */
    public function getLastweekview()
    {
        return $this->lastweekview;
    }

    /**
     * Set lastmonthview
     *
     * @param integer $lastmonthview
     * @return Estate
     */
    public function setLastmonthview($lastmonthview)
    {
        $this->lastmonthview = $lastmonthview;

        return $this;
    }

    /**
     * Get lastmonthview
     *
     * @return integer
     */
    public function getLastmonthview()
    {
        return $this->lastmonthview;
    }

    /**
     * Set lat
     *
     * @param string $lat
     * @return Estate
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param string $lng
     * @return Estate
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return string
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * Add photo
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\Photo $photo
     * @return Estate
     */
    public function addPhoto(\Caravane\Bundle\EstateBundle\Entity\Photo $photo)
    {
        $this->photo[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\Photo $photo
     */
    public function removePhoto(\Caravane\Bundle\EstateBundle\Entity\Photo $photo)
    {
        $this->photo->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        foreach($this->photo as $photo) {

           if(
            !file_exists(__DIR__."/../../../../../web/photos/big/".$photo->getFilename())
            || $photo->getFilename()==''
            || is_dir(__DIR__."/../../../../../web/photos/big/".$photo->getFilename())

            ) {
                //$photo->setFilename("dummy.png");
                $this->removePhoto($photo);
            }
        }

        return $this->photo;
    }

    /**
     * Get photo
     *
     * @return \Caravane/Bundle/EstateBundle\Entity\Photo
     */
    public function getPhoto()
    {
        $photos=$this->getPhotos();
        //echo count($photos);

        if(count($photos)==0) {
            $photo=new \Caravane\Bundle\EstateBundle\Entity\Photo();
            $photo->setFilename("dummy.png");
            $photo->setEstate($this);
            $photo->setRanking(1);
            return $photo;
        }
        else {
            foreach($photos as $p) {
                return $p;
            }

        }

        return null;
    }

    public function getDefaultPict()
    {
        $this->getPhotos();
        return $this->getPhoto();
    }

    /**
     * Set area
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\Area $area
     * @return Estate
     */
    public function setArea(\Caravane\Bundle\EstateBundle\Entity\Area $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return \Caravane\Bundle\EstateBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set category
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\Category $category
     * @return Estate
     */
    public function setCategory(\Caravane\Bundle\EstateBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Caravane\Bundle\EstateBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }


    /**
     * Set zone
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\Zone $zone
     * @return Estate
     */
    public function setZone(\Caravane\Bundle\EstateBundle\Entity\Zone $zone = null)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return \Caravane\Bundle\EstateBundle\Entity\Zone
     */
    public function getZone()
    {
        return $this->zone;
    }

    public function isUpdated() {
        $date = new \Datetime("now - 15 days");
         if($this->updatedOn>$date) {
            return true;
        }
        return false;
    }


    public function isNew() {
        $date = new \Datetime("now - 15 days");
        if($this->createdOn>$date) {
            return true;
        }
        return false;
    }




    /**
     * Set isNewBuilding
     *
     * @param boolean $isNewBuilding
     * @return Estate
     */
    public function setIsNewBuilding($isNewBuilding)
    {
        $this->isNewBuilding = $isNewBuilding;

        return $this;
    }

    /**
     * Get isNewBuilding
     *
     * @return boolean
     */
    public function getIsNewBuilding()
    {
        return $this->isNewBuilding;
    }

    /**
     * Add user
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\UserEstate $user
     * @return Estate
     */
    public function addUser(\Caravane\Bundle\EstateBundle\Entity\UserEstate $user)
    {
        $this->user[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Caravane\Bundle\EstateBundle\Entity\UserEstate $user
     */
    public function removeUser(\Caravane\Bundle\EstateBundle\Entity\UserEstate $user)
    {
        $this->user->removeElement($user);
    }

    /**
     * Get user
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUser()
    {
        return $this->user;
    }
}
