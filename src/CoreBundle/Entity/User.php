<?php

namespace App\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\UserRepository")
 *
 * @UniqueEntity(fields={"email"}, message="This email is already taken.")
 * @UniqueEntity(fields={"username"}, message="This username is already taken.")
 */
class User implements UserInterface, \Serializable
{
    /**
     * Alias for user.
     */
    const ALIAS = 'u';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, nullable=true)
     */
     private $apiToken;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, unique=true)
     */
    private $username;

    /**
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     max=50,
     *     maxMessage = "Your email must be 50 characters only."
     * )
     *
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $roles = [];

    /**
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     max=50,
     *     maxMessage = "Your first name must be 50 characters only."
     * )
     *
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=50)
     */
    private $firstName;

    /**
     * @Assert\NotBlank()
     *
     * @var string
     *
     * @ORM\Column(name="middle_name", type="string", length=255)
     */
    private $middleName;

    /**
     * @Assert\NotBlank()
     *
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     */
    private $lastName;

    /**
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     max=50,
     *     maxMessage = "Your designation must be 50 characters only."
     * )
     *
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=50, nullable=true)
     */
    private $designation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="started_at", type="date", nullable=true)
     */
    private $startedAt;

    /**
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     max=50,
     *     maxMessage = "Your location must be 50 characters only."
     * )
     *
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=50, nullable=true)
     */
    private $location;

    /**
     * @Assert\Length(
     *     max=50,
     *     maxMessage = "Your skype must be 50 characters only."
     * )
     *
     * @var string
     *
     * @ORM\Column(name="skype", type="string", length=50, nullable=true)
     */
    private $skype;

    /**
     * @Assert\Length(
     *     max=50,
     *     maxMessage = "Your slack must be 50 characters only."
     * )
     *
     * @var string
     *
     * @ORM\Column(name="slack", type="string", length=255, nullable=true)
     */
    private $slack;

    /**
     * @Assert\NotBlank()
     *
     * @Assert\Length(
     *     max=700,
     *     maxMessage = "The description must be 500 characters only."
     * )
     *
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="text", nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="hover_avatar", type="text", nullable=true)
     */
    private $hoverAvatar;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Article", mappedBy="author")
     */
    private $articles;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->isActive = true;
        $this->articles = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * get api token.
     *
     * @return string
     */
    public function getApiToken()
    {
        return $this->apiToken;
    }

    /**
     * set api token.
     *
     * @param string $apiToken
     *
     * @return User
     */
    public function setApiToken($apiToken)
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * get username.
     *
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * set username.
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * get email.
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * get password.
     *
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * set password.
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * get isActive.
     *
     * @return string
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * get roles.
     *
     * @return array
     */
    public function getRoles()
    {
        $roles = $this->roles;

        return $roles;
    }

    /**
     * set roles.
     *
     * @param array $roles
     *
     * @return User
     */
    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get first name.
     *
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set first name.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get middle name.
     *
     * @return null|string
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /**
     * Set middle name.
     *
     * @param string $middleName
     *
     * @return User
     */
    public function setMiddleName(string $middleName)
    {
        $this->middleName = $middleName;

        return $this;
    }

    /**
     * Get last name.
     *
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set last name.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get designation.
     *
     * @return string
     */
    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    /**
     * Set designation.
     *
     * @param string $designation
     *
     * @return User
     */
    public function setDesignation(string $designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get started at.
     *
     * @return \DateTime
     */
    public function getStartedAt(): ?\DateTime
    {
        return $this->startedAt;
    }

    /**
     * Set started at.
     *
     * @param \DateTime $startedAt
     *
     * @return User
     */
    public function setStartedAt(\DateTime $startedAt)
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    /**
     * Get location.
     *
     * @return null|string
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * Set location.
     *
     * @param string $location
     *
     * @return User
     */
    public function setLocation(string $location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get skype.
     *
     * @return string
     */
    public function getSkype(): ?string
    {
        return $this->skype;
    }

    /**
     * Set skype.
     *
     * @param string $skype
     *
     * @return User
     */
    public function setSkype(string $skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * Get slack.
     *
     * @return string
     */
    public function getSlack(): ?string
    {
        return $this->slack;
    }

    /**
     * Set slack.
     *
     * @param string $slack
     *
     * @return User
     */
    public function setSlack(string $slack)
    {
        $this->slack = $slack;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return User
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set Avatar.
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar.
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set hover avatar.
     *
     * @param string $hoverAvatar
     *
     * @return User
     */
    public function setHoverAvatar($hoverAvatar)
    {
        $this->hoverAvatar = $hoverAvatar;

        return $this;
    }

    /**
     * Get hover avatar.
     *
     * @return string
     */
    public function getHoverAvatar()
    {
        return $this->hoverAvatar;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return User
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return null|string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Add a articles in array collection.
     *
     * @param Article $articles
     *
     * @return User
     */
    public function addArticle(Article $articles)
    {
        if (!$this->articles->contains($articles)) {
            $this->articles->add($articles);
        }

        return $this;
    }

    /**
     * Remove a article in array collection.
     *
     * @param Article $articles
     *
     * @return User.
     */
    public function removeArticle(Article $articles)
    {
        if ($this->articles->contains($articles)) {
            $this->articles->removeElement($articles);
        }

        return $this;
    }

    /**
     * Get articles.
     *
     * @return ArrayCollection
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * get salt.
     *
     * @return null|string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
    }

    /**
     * Serialize user.
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    /**
     * Unserialize user.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
            ) = unserialize($serialized);
    }

    /**
     * Return string name.
     *
     * @return string
     */
    public function __toString(): ?string
    {
        return (string) $this->getFirstName() . $this->getLastName();
    }
}