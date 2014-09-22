<?php

namespace Caravane\Bundle\EstateBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estate
 *
 * @ORM\Table(name="Estate")
 * @ORM\Entity
 */
class Estate
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
     * @var \DateTime
     *
     * @ORM\Column(name="datein", type="date", nullable=true)
     */
    private $datein;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updateDate", type="date", nullable=false)
     */
    private $updatedate;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=150, nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=8, scale=0, nullable=true)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="oldPrix", type="decimal", precision=8, scale=0, nullable=false)
     */
    private $oldprix;

    /**
     * @var string
     *
     * @ORM\Column(name="locfr", type="string", length=50, nullable=true)
     */
    private $locfr;

    /**
     * @var string
     *
     * @ORM\Column(name="locuk", type="string", length=50, nullable=true)
     */
    private $locuk;

    /**
     * @var integer
     *
     * @ORM\Column(name="area_id", type="integer", nullable=false)
     */
    private $areaId;

    /**
     * @var string
     *
     * @ORM\Column(name="zone", type="string", length=20, nullable=false)
     */
    private $zone;

    /**
     * @var string
     *
     * @ORM\Column(name="shortdescrfr", type="string", length=255, nullable=false)
     */
    private $shortdescrfr;

    /**
     * @var string
     *
     * @ORM\Column(name="shortdescren", type="string", length=255, nullable=false)
     */
    private $shortdescren;

    /**
     * @var string
     *
     * @ORM\Column(name="descrfr", type="text", nullable=true)
     */
    private $descrfr;

    /**
     * @var string
     *
     * @ORM\Column(name="descren", type="text", nullable=true)
     */
    private $descren;

    /**
     * @var string
     *
     * @ORM\Column(name="vendu", type="string", length=1, nullable=true)
     */
    private $vendu;

    /**
     * @var string
     *
     * @ORM\Column(name="surdemande", type="string", length=1, nullable=true)
     */
    private $surdemande;

    /**
     * @var string
     *
     * @ORM\Column(name="actif", type="string", length=1, nullable=true)
     */
    private $actif;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=1, nullable=true)
     */
    private $location;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=20, nullable=true)
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="enoption", type="string", length=1, nullable=true)
     */
    private $enoption;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name = '';

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=100, nullable=false)
     */
    private $type = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="zip", type="integer", nullable=false)
     */
    private $zip = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="google_map", type="text", nullable=false)
     */
    private $googleMap;

    /**
     * @var string
     *
     * @ORM\Column(name="moredescrfr", type="text", nullable=false)
     */
    private $moredescrfr;

    /**
     * @var integer
     *
     * @ORM\Column(name="area", type="integer", nullable=false)
     */
    private $area = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="rooms", type="integer", nullable=false)
     */
    private $rooms = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="bathrooms", type="integer", nullable=false)
     */
    private $bathrooms = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="garages", type="integer", nullable=false)
     */
    private $garages = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="garden", type="string", length=10, nullable=false)
     */
    private $garden = '';

    /**
     * @var string
     *
     * @ORM\Column(name="viewable", type="string", length=10, nullable=false)
     */
    private $viewable = '';

    /**
     * @var string
     *
     * @ORM\Column(name="public", type="string", length=10, nullable=false)
     */
    private $public = 'checked';

    /**
     * @var string
     *
     * @ORM\Column(name="photobck", type="string", length=20, nullable=false)
     */
    private $photobck = '';

    /**
     * @var string
     *
     * @ORM\Column(name="tphoto", type="string", length=15, nullable=false)
     */
    private $tphoto = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="dayview", type="integer", nullable=false)
     */
    private $dayview;

    /**
     * @var integer
     *
     * @ORM\Column(name="weekview", type="integer", nullable=false)
     */
    private $weekview;

    /**
     * @var integer
     *
     * @ORM\Column(name="monthview", type="integer", nullable=false)
     */
    private $monthview;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalview", type="integer", nullable=false)
     */
    private $totalview;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastdayview", type="integer", nullable=false)
     */
    private $lastdayview;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastweekview", type="integer", nullable=false)
     */
    private $lastweekview;

    /**
     * @var integer
     *
     * @ORM\Column(name="lastmonthview", type="integer", nullable=false)
     */
    private $lastmonthview;

    /**
     * @var string
     *
     * @ORM\Column(name="Lat", type="string", length=50, nullable=false)
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="Lng", type="string", length=50, nullable=false)
     */
    private $lng;

    /**
     * @var boolean
     *
     * @ORM\Column(name="processed", type="boolean", nullable=false)
     */
    private $processed;



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
     * Set datein
     *
     * @param \DateTime $datein
     * @return Estate
     */
    public function setDatein($datein)
    {
        $this->datein = $datein;

        return $this;
    }

    /**
     * Get datein
     *
     * @return \DateTime 
     */
    public function getDatein()
    {
        return $this->datein;
    }

    /**
     * Set updatedate
     *
     * @param \DateTime $updatedate
     * @return Estate
     */
    public function setUpdatedate($updatedate)
    {
        $this->updatedate = $updatedate;

        return $this;
    }

    /**
     * Get updatedate
     *
     * @return \DateTime 
     */
    public function getUpdatedate()
    {
        return $this->updatedate;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return Estate
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
     * Set areaId
     *
     * @param integer $areaId
     * @return Estate
     */
    public function setAreaId($areaId)
    {
        $this->areaId = $areaId;

        return $this;
    }

    /**
     * Get areaId
     *
     * @return integer 
     */
    public function getAreaId()
    {
        return $this->areaId;
    }

    /**
     * Set zone
     *
     * @param string $zone
     * @return Estate
     */
    public function setZone($zone)
    {
        $this->zone = $zone;

        return $this;
    }

    /**
     * Get zone
     *
     * @return string 
     */
    public function getZone()
    {
        return $this->zone;
    }

    /**
     * Set shortdescrfr
     *
     * @param string $shortdescrfr
     * @return Estate
     */
    public function setShortdescrfr($shortdescrfr)
    {
        $this->shortdescrfr = $shortdescrfr;

        return $this;
    }

    /**
     * Get shortdescrfr
     *
     * @return string 
     */
    public function getShortdescrfr()
    {
        return $this->shortdescrfr;
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
     * Set descrfr
     *
     * @param string $descrfr
     * @return Estate
     */
    public function setDescrfr($descrfr)
    {
        $this->descrfr = $descrfr;

        return $this;
    }

    /**
     * Get descrfr
     *
     * @return string 
     */
    public function getDescrfr()
    {
        return $this->descrfr;
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
     * Set vendu
     *
     * @param string $vendu
     * @return Estate
     */
    public function setVendu($vendu)
    {
        $this->vendu = $vendu;

        return $this;
    }

    /**
     * Get vendu
     *
     * @return string 
     */
    public function getVendu()
    {
        return $this->vendu;
    }

    /**
     * Set surdemande
     *
     * @param string $surdemande
     * @return Estate
     */
    public function setSurdemande($surdemande)
    {
        $this->surdemande = $surdemande;

        return $this;
    }

    /**
     * Get surdemande
     *
     * @return string 
     */
    public function getSurdemande()
    {
        return $this->surdemande;
    }

    /**
     * Set actif
     *
     * @param string $actif
     * @return Estate
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return string 
     */
    public function getActif()
    {
        return $this->actif;
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
     * Set type
     *
     * @param string $type
     * @return Estate
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
     * Set area
     *
     * @param integer $area
     * @return Estate
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return integer 
     */
    public function getArea()
    {
        return $this->area;
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
     * Set public
     *
     * @param string $public
     * @return Estate
     */
    public function setPublic($public)
    {
        $this->public = $public;

        return $this;
    }

    /**
     * Get public
     *
     * @return string 
     */
    public function getPublic()
    {
        return $this->public;
    }

    /**
     * Set photobck
     *
     * @param string $photobck
     * @return Estate
     */
    public function setPhotobck($photobck)
    {
        $this->photobck = $photobck;

        return $this;
    }

    /**
     * Get photobck
     *
     * @return string 
     */
    public function getPhotobck()
    {
        return $this->photobck;
    }

    /**
     * Set tphoto
     *
     * @param string $tphoto
     * @return Estate
     */
    public function setTphoto($tphoto)
    {
        $this->tphoto = $tphoto;

        return $this;
    }

    /**
     * Get tphoto
     *
     * @return string 
     */
    public function getTphoto()
    {
        return $this->tphoto;
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
     * Set processed
     *
     * @param boolean $processed
     * @return Estate
     */
    public function setProcessed($processed)
    {
        $this->processed = $processed;

        return $this;
    }

    /**
     * Get processed
     *
     * @return boolean 
     */
    public function getProcessed()
    {
        return $this->processed;
    }
}
