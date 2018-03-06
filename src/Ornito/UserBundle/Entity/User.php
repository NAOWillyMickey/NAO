<?php

namespace Ornito\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Ornito\ObservationBundle\Entity\Watching;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Ornito\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{

    const CONFIRMED = "confirmed";
    const PENDING = "pending";
    const DECLINED = "declined";
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=64, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=64, nullable=true)
     */
    private $lastName;

    /**
     * @Assert\Valid()
     * @ORM\OneToOne(targetEntity="Ornito\UserBundle\Entity\Avatar", cascade={"persist", "remove"})
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string", length=64, nullable=true)
     */
    private $facebook;

    /**
     * @var string
     *
     * @ORM\Column(name="google", type="string", length=64, nullable=true)
     */
    private $googlePlus;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=64, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="pinterest", type="string", length=64, nullable=true)
     */
    private $pinterest;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin", type="string", length=64, nullable=true)
     */
    private $linkedin;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="text", nullable=true)
     */
    private $bio;

    /**
     * @ORM\OneToMany(targetEntity="Ornito\ObservationBundle\Entity\Watching", mappedBy="user", cascade={"persist", "remove"})
     */
    private $watchings;

    /**
     * @var string
     *
     * @ORM\Column(name="naturalistPromote", type="string", length=64, nullable=true)
     */
    private $naturalistPromote;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="$registerAt", type="datetime", nullable=true)
     */
    private $registerAt;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // Add role by default
        $this->registerAt = new \Datetime();
        $this->addRole("ROLE_MEMBER");
        $this->watchings = new ArrayCollection();
    }

    /**
     * Add watching.
     *
     * @param \Ornito\ObservationBundle\Entity\Watching $watching
     *
     * @return User
     */
    public function addWatching(Watching $watching)
    {
        $this->watchings[] = $watching;
        $watching->setUser($this);
        return $this;
    }

    /**
     * Remove watching.
     *
     * @param \Ornito\ObservationBundle\Entity\Watching $watching
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeWatching(Watching $watching)
    {
        return $this->watchings->removeElement($watching);
    }

    /**
     * Get watchings.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWatchings()
    {
        return $this->watchings;
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set facebook.
     *
     * @param string|null $facebook
     *
     * @return User
     */
    public function setFacebook($facebook = null)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook.
     *
     * @return string|null
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set googlePlus.
     *
     * @param string|null $googlePlus
     *
     * @return User
     */
    public function setGooglePlus($googlePlus = null)
    {
        $this->googlePlus = $googlePlus;

        return $this;
    }

    /**
     * Get googlePlus.
     *
     * @return string|null
     */
    public function getGooglePlus()
    {
        return $this->googlePlus;
    }

    /**
     * Set twitter.
     *
     * @param string|null $twitter
     *
     * @return User
     */
    public function setTwitter($twitter = null)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter.
     *
     * @return string|null
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set pinterest.
     *
     * @param string|null $pinterest
     *
     * @return User
     */
    public function setPinterest($pinterest = null)
    {
        $this->pinterest = $pinterest;

        return $this;
    }

    /**
     * Get pinterest.
     *
     * @return string|null
     */
    public function getPinterest()
    {
        return $this->pinterest;
    }

    /**
     * Set linkedin.
     *
     * @param string|null $linkedin
     *
     * @return User
     */
    public function setLinkedin($linkedin = null)
    {
        $this->linkedin = $linkedin;

        return $this;
    }

    /**
     * Get linkedin.
     *
     * @return string|null
     */
    public function getLinkedin()
    {
        return $this->linkedin;
    }

    /**
     * Set bio.
     *
     * @param string|null $bio
     *
     * @return User
     */
    public function setBio($bio = null)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio.
     *
     * @return string|null
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set naturalistPromote.
     *
     * @param string|null $naturalistPromote
     *
     * @return User
     */
    public function setNaturalistPromote($naturalistPromote = null)
    {
        $this->naturalistPromote = $naturalistPromote;

        return $this;
    }

    /**
     * Get naturalistPromote.
     *
     * @return string|null
     */
    public function getNaturalistPromote()
    {
        return $this->naturalistPromote;
    }

    /**
     * Set registerAt
     *
     * @param \DateTime $registerAt
     *
     * @return User
     */
    public function setRegisterAt($registerAt)
    {
        $this->registerAt = $registerAt;
        return $this;
    }
    /**
     * Get registerAt
     *
     * @return \DateTime
     */
    public function getRegisterAt()
    {
        return $this->registerAt;
    }

    /**
     * Set avatar.
     *
     * @param Avatar|null $avatar
     *
     * @return User
     */
    public function setAvatar(Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return \Ornito\UserBundle\Entity\Avatar|null
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

}
