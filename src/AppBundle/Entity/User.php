<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class User
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 *
 * @UniqueEntity(
 *     fields={"email", "nick"},
 *     message="Ya existe"
 * )
 */
class User implements UserInterface, \Serializable
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
     * @var string
     *
     * @ORM\Column(name="role", type="string")
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     *
     * @Assert\NotBlank(
     *     message="El email es obligatorio"
     * )
     *
     * @Assert\Email(
     *     message="El formato de email no es correcto",
     *     checkMX=false
     * )
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string")
     *
     * @Assert\NotBlank(
     *     message="El nombre es obligatorio"
     * )
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string")
     *
     * @Assert\NotBlank(
     *     message="Los apellidos son obligatorios"
     * )
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="nick", type="string")
     *
     * @Assert\NotBlank(
     *     message="El nick es obligatorio"
     * )
     */
    private $nick;

    /**
     * @var string
     *
     * @ORM\Column(name="bio", type="string", nullable=true)
     */
    private $bio;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="string")
     */
    private $active;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", nullable=true)
     */
    private $image;

    /**
     * @var Publication[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Publication", mappedBy="user")
     */
    private $publications;

    /**
     * @var Like[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Like", mappedBy="user")
     */
    private $likes;

    /**
     * @var Message[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="sender")
     */
    private $messagesAsSender;

    /**
     * @var Message[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Message", mappedBy="receiver")
     */
    private $messagesAsReceiver;

    /**
     * @var Notification[]
     * 
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Notification", mappedBy="user")
     */
    private $notifications;

    /**
     * @var Following[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Following", mappedBy="following")
     */
    private $followingsAsFollowing;

    /**
     * @var Following[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Following", mappedBy="followed")
     */
    private $followingsAsFollowed;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->publications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->likes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messagesAsSender = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messagesAsReceiver = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->followingsAsFollowing = new \Doctrine\Common\Collections\ArrayCollection();
        $this->followingsAsFollowed = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set role
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstName
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
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
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
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set nick
     *
     * @param string $nick
     *
     * @return User
     */
    public function setNick($nick)
    {
        $this->nick = $nick;

        return $this;
    }

    /**
     * Get nick
     *
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * Set bio
     *
     * @param string $bio
     *
     * @return User
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Get bio
     *
     * @return string
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set active
     *
     * @param string $active
     *
     * @return User
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return string
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return User
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add publication
     *
     * @param \AppBundle\Entity\Publication $publication
     *
     * @return User
     */
    public function addPublication(\AppBundle\Entity\Publication $publication)
    {
        $this->publications[] = $publication;

        return $this;
    }

    /**
     * Remove publication
     *
     * @param \AppBundle\Entity\Publication $publication
     */
    public function removePublication(\AppBundle\Entity\Publication $publication)
    {
        $this->publications->removeElement($publication);
    }

    /**
     * Get publications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublications()
    {
        return $this->publications;
    }

    /**
     * Add like
     *
     * @param \AppBundle\Entity\Like $like
     *
     * @return User
     */
    public function addLike(\AppBundle\Entity\Like $like)
    {
        $this->likes[] = $like;

        return $this;
    }

    /**
     * Remove like
     *
     * @param \AppBundle\Entity\Like $like
     */
    public function removeLike(\AppBundle\Entity\Like $like)
    {
        $this->likes->removeElement($like);
    }

    /**
     * Get likes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Add messagesAsSender
     *
     * @param \AppBundle\Entity\Message $messagesAsSender
     *
     * @return User
     */
    public function addMessagesAsSender(\AppBundle\Entity\Message $messagesAsSender)
    {
        $this->messagesAsSender[] = $messagesAsSender;

        return $this;
    }

    /**
     * Remove messagesAsSender
     *
     * @param \AppBundle\Entity\Message $messagesAsSender
     */
    public function removeMessagesAsSender(\AppBundle\Entity\Message $messagesAsSender)
    {
        $this->messagesAsSender->removeElement($messagesAsSender);
    }

    /**
     * Get messagesAsSender
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessagesAsSender()
    {
        return $this->messagesAsSender;
    }

    /**
     * Add messagesAsReceiver
     *
     * @param \AppBundle\Entity\Message $messagesAsReceiver
     *
     * @return User
     */
    public function addMessagesAsReceiver(\AppBundle\Entity\Message $messagesAsReceiver)
    {
        $this->messagesAsReceiver[] = $messagesAsReceiver;

        return $this;
    }

    /**
     * Remove messagesAsReceiver
     *
     * @param \AppBundle\Entity\Message $messagesAsReceiver
     */
    public function removeMessagesAsReceiver(\AppBundle\Entity\Message $messagesAsReceiver)
    {
        $this->messagesAsReceiver->removeElement($messagesAsReceiver);
    }

    /**
     * Get messagesAsReceiver
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessagesAsReceiver()
    {
        return $this->messagesAsReceiver;
    }

    /**
     * Add notification
     *
     * @param \AppBundle\Entity\Notification $notification
     *
     * @return User
     */
    public function addNotification(\AppBundle\Entity\Notification $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \AppBundle\Entity\Notification $notification
     */
    public function removeNotification(\AppBundle\Entity\Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Add followingsAsFollowing
     *
     * @param \AppBundle\Entity\Following $followingsAsFollowing
     *
     * @return User
     */
    public function addFollowingsAsFollowing(\AppBundle\Entity\Following $followingsAsFollowing)
    {
        $this->followingsAsFollowing[] = $followingsAsFollowing;

        return $this;
    }

    /**
     * Remove followingsAsFollowing
     *
     * @param \AppBundle\Entity\Following $followingsAsFollowing
     */
    public function removeFollowingsAsFollowing(\AppBundle\Entity\Following $followingsAsFollowing)
    {
        $this->followingsAsFollowing->removeElement($followingsAsFollowing);
    }

    /**
     * Get followingsAsFollowing
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowingsAsFollowing()
    {
        return $this->followingsAsFollowing;
    }

    /**
     * Add followingsAsFollowed
     *
     * @param \AppBundle\Entity\Following $followingsAsFollowed
     *
     * @return User
     */
    public function addFollowingsAsFollowed(\AppBundle\Entity\Following $followingsAsFollowed)
    {
        $this->followingsAsFollowed[] = $followingsAsFollowed;

        return $this;
    }

    /**
     * Remove followingsAsFollowed
     *
     * @param \AppBundle\Entity\Following $followingsAsFollowed
     */
    public function removeFollowingsAsFollowed(\AppBundle\Entity\Following $followingsAsFollowed)
    {
        $this->followingsAsFollowed->removeElement($followingsAsFollowed);
    }

    /**
     * Get followingsAsFollowed
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowingsAsFollowed()
    {
        return $this->followingsAsFollowed;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return array(
            'ROLE_USER',
            'ROLE_ADMIN'
        );
    }

    /**
     *
     */
    public function eraseCredentials()
    {

    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->firstName;
    }
}
