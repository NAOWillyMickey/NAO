<?php

namespace Ornito\ResearchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Species
 *
 * @ORM\Table(name="species")
 * @ORM\Entity(repositoryClass="Ornito\ResearchBundle\Repository\SpeciesRepository")
 */
class Species
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ordre", type="string", length=32)
     */
    private $ordre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="family", type="string", length=32, nullable=true)
     */
    private $family;

    /**
     * @var string
     *
     * @ORM\Column(name="scientific_name", type="string", length=64)
     */
    private $scientificName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="author", type="string", length=32, nullable=true)
     */
    private $author;

    /**
     * @var string|null
     *
     * @ORM\Column(name="vern_fr", type="string", length=64, nullable=true)
     */
    private $vernFr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="vern_en", type="string", length=64, nullable=true)
     */
    private $vernEn;

    /**
     * @var string|null
     *
     * @ORM\Column(name="protected_status", type="string", length=16, nullable=true)
     */
    private $protectedStatus;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=64, nullable=true)
     */
    private $url;

    /**
     * Species constructor.
     *
     * @param string $ordre
     * @param string $family
     * @param string $author
     * @param string $scientificName
     * @param string $vernFr
     * @param string $vernEn
     * @param string $protectedStatus
     * @param string $url
     */
    public function __construct($ordre, $family, $author, $scientificName, $vernFr, $vernEn, $protectedStatus, $url)
    {
        $this->ordre = $ordre;
        $this->family = $family;
        $this->author = $author;
        $this->scientificName = $scientificName;
        $this->vernFr = $vernFr;
        $this->vernEn = $vernEn;
        $this->protectedStatus = $protectedStatus;
        $this->url = $url;
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
     * Set ordre.
     *
     * @param string $ordre
     *
     * @return Species
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre.
     *
     * @return string
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set family.
     *
     * @param string|null $family
     *
     * @return Species
     */
    public function setFamily($family = null)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family.
     *
     * @return string|null
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Set scientificName.
     *
     * @param string $scientificName
     *
     * @return Species
     */
    public function setScientificName($scientificName)
    {
        $this->scientificName = $scientificName;

        return $this;
    }

    /**
     * Get scientificName.
     *
     * @return string
     */
    public function getScientificName()
    {
        return $this->scientificName;
    }

    /**
     * Set author.
     *
     * @param string|null $author
     *
     * @return Species
     */
    public function setAuthor($author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return string|null
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set vernFr.
     *
     * @param string|null $vernFr
     *
     * @return Species
     */
    public function setVernFr($vernFr = null)
    {
        $this->vernFr = $vernFr;

        return $this;
    }

    /**
     * Get vernFr.
     *
     * @return string|null
     */
    public function getVernFr()
    {
        return $this->vernFr;
    }

    /**
     * Set vernEn.
     *
     * @param string|null $vernEn
     *
     * @return Species
     */
    public function setVernEn($vernEn = null)
    {
        $this->vernEn = $vernEn;

        return $this;
    }

    /**
     * Get vernEn.
     *
     * @return string|null
     */
    public function getVernEn()
    {
        return $this->vernEn;
    }

    /**
     * Set protectedStatus.
     *
     * @param string|null $protectedStatus
     *
     * @return Species
     */
    public function setProtectedStatus($protectedStatus = null)
    {
        $this->protectedStatus = $protectedStatus;

        return $this;
    }

    /**
     * Get protectedStatus.
     *
     * @return string|null
     */
    public function getProtectedStatus()
    {
        return $this->protectedStatus;
    }

    /**
     * Set url.
     *
     * @param string|null $url
     *
     * @return Species
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }
}
