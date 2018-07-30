<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Following
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FollowingRepository")
 * @ORM\Table(name="following")
 */
class Following
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="followingsAsFollowing")
     */
    private $following;

    /**
     * @var User[]
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="followingsAsFollowed")
     */
    private $followed;

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
     * Set following
     *
     * @param \AppBundle\Entity\User $following
     *
     * @return Following
     */
    public function setFollowing(\AppBundle\Entity\User $following = null)
    {
        $this->following = $following;

        return $this;
    }

    /**
     * Get following
     *
     * @return \AppBundle\Entity\User
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * Set followed
     *
     * @param \AppBundle\Entity\User $followed
     *
     * @return Following
     */
    public function setFollowed(\AppBundle\Entity\User $followed = null)
    {
        $this->followed = $followed;

        return $this;
    }

    /**
     * Get followed
     *
     * @return \AppBundle\Entity\User
     */
    public function getFollowed()
    {
        return $this->followed;
    }
}
