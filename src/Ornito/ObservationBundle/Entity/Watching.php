<?php

namespace Ornito\ObservationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="validate_status", type="string", length=16)
     */
    private $validateStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;

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
}
