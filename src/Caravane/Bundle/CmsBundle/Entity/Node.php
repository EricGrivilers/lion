<?php

namespace Caravane\Bundle\CmsBundle\Entity;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\PreSerialize;


use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;


use Doctrine\ORM\Mapping as ORM;

/**
 * Node
 *
 * @ORM\Table(name="Node")
 * @ORM\Entity
 * @ExclusionPolicy("all")
 */
class Node implements Translatable
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
     * @var text
     *
     * @Gedmo\Translatable
     * @ORM\Column(name="firstname", type="text", nullable=true)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="uri", type="string", length=255, nullable=false)
     */
    private $uri;

   /**
     * @Gedmo\Locale
     * Used locale to override Translation listener`s locale
     * this is not a mapped field of entity metadata, just a simple property
     */
    private $locale;


    public function __toString() {
        return $this->uri;
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
     * Set content
     *
     * @param string $content
     * @return Node
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
     * Set uri
     *
     * @param string $uri
     * @return Node
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri
     *
     * @return string 
     */
    public function getUri()
    {
        return $this->uri;
    }

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }

}
