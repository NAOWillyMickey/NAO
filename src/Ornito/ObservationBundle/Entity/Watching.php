<?php

namespace Ornito\ObservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ornito\TaxrefBundle\Entity\Species;
use Ornito\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Watching
 *
 * @ORM\Table(name="watching")
 * @ORM\Entity(repositoryClass="Ornito\ObservationBundle\Repository\WatchingRepository")
 */
class Watching
{
    const UNTREATED = "untreated";
    const ATTESTED = "attested";
    const REJECTED = "rejected";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime(message="Cette valeur n'est pas une date-heure valide.")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="validate_status", type="string", length=16)
     * @Assert\Choice(choices={"untreated", "attested", "rejected"}, message="Choisir un statut de validation correct")
     */
    private $validateStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Type(
     *     type="string",
     *     message="Cette valeur n'est pas une chaine de caractères."
     * )
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     * @Assert\Type(
     *     type="float",
     *     message="Cette valeur n'est pas un nombre décimal."
     * )
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     * @Assert\Type(
     *     type="float",
     *     message="Cette valeur n'est pas un nombre décimal."
     * )
     */
    private $longitude;

    /**
     * var string
     *
     * @ORM\Column(name="title", type="string")
     * @Assert\Type(
     *     type="string",
     *     message="Cette valeur n'est pas une chaine de caractères."
     * )
     */
    private $title;

    /**
     * @Assert\Valid()
     * @ORM\OneToOne(targetEntity="Ornito\ObservationBundle\Entity\Image", cascade={"persist", "remove"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="Ornito\UserBundle\Entity\User", inversedBy="watchings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Ornito\TaxrefBundle\Entity\Species")
     * @ORM\JoinColumn(nullable=false)
     */
    private $species;

    /**
     * Observation constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->validateStatus = self::UNTREATED;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return Watching
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set validateStatus.
     *
     * @param string $validateStatus
     *
     * @return Watching
     */
    public function setValidateStatus($validateStatus)
    {
        $this->validateStatus = $validateStatus;

        return $this;
    }

    /**
     * Get validateStatus.
     *
     * @return string
     */
    public function getValidateStatus()
    {
        return $this->validateStatus;
    }

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Watching
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set latitude.
     *
     * @param float $latitude
     *
     * @return Watching
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude.
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude.
     *
     * @param float $longitude
     *
     * @return Watching
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude.
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set image.
     *
     * @param Image|null $image
     *
     * @return Watching
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image.
     *
     * @return \Ornito\ObservationBundle\Entity\Image|null
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Watching
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set user.
     *
     * @param \Ornito\UserBundle\Entity\User $user
     *
     * @return Watching
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Ornito\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set species.
     *
     * @param \Ornito\TaxrefBundle\Entity\Species $species
     *
     * @return Watching
     */
    public function setSpecies(Species $species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species.
     *
     * @return \Ornito\TaxrefBundle\Entity\Species
     */
    public function getSpecies()
    {
        return $this->species;
    }
}
