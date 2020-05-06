<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Role")
     * @ORM\JoinColumn(nullable=false)
     */
    private $role;

    /**
     * @ORM\Column(type="guid")
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserSavedPlace", mappedBy="user", orphanRemoval=true)
     */
    private $userSavedPlaces;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserFeedbackPlace", mappedBy="user", orphanRemoval=true)
     */
    private $userFeedbackPlaces;

    public function __construct()
    {
        $this->userSavedPlaces = new ArrayCollection();
        $this->userFeedbackPlaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getUsername(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?role
    {
        return $this->role;
    }

    public function getRoles(): ?role
    {
        return $this->role;
    }

    public function setRole(?role $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection|UserSavedPlace[]
     */
    public function getUserSavedPlaces(): Collection
    {
        return $this->userSavedPlaces;
    }

    public function addUserSavedPlace(UserSavedPlace $userSavedPlace): self
    {
        if (!$this->userSavedPlaces->contains($userSavedPlace)) {
            $this->userSavedPlaces[] = $userSavedPlace;
            $userSavedPlace->setUser($this);
        }

        return $this;
    }

    public function removeUserSavedPlace(UserSavedPlace $userSavedPlace): self
    {
        if ($this->userSavedPlaces->contains($userSavedPlace)) {
            $this->userSavedPlaces->removeElement($userSavedPlace);
            // set the owning side to null (unless already changed)
            if ($userSavedPlace->getUser() === $this) {
                $userSavedPlace->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserFeedbackPlace[]
     */
    public function getUserFeedbackPlaces(): Collection
    {
        return $this->userFeedbackPlaces;
    }

    public function addUserFeedbackPlace(UserFeedbackPlace $userFeedbackPlace): self
    {
        if (!$this->userFeedbackPlaces->contains($userFeedbackPlace)) {
            $this->userFeedbackPlaces[] = $userFeedbackPlace;
            $userFeedbackPlace->setUser($this);
        }

        return $this;
    }

    public function removeUserFeedbackPlace(UserFeedbackPlace $userFeedbackPlace): self
    {
        if ($this->userFeedbackPlaces->contains($userFeedbackPlace)) {
            $this->userFeedbackPlaces->removeElement($userFeedbackPlace);
            // set the owning side to null (unless already changed)
            if ($userFeedbackPlace->getUser() === $this) {
                $userFeedbackPlace->setUser(null);
            }
        }

        return $this;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


}
