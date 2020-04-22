<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceRepository")
 */
class Place
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $phone_number;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WorkTime", mappedBy="place", orphanRemoval=true)
     */
    private $workTimes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="place", orphanRemoval=true)
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PlaceHasService", mappedBy="place")
     */
    private $placeHasServices;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserFeedbackPlace", mappedBy="place", orphanRemoval=true)
     */
    private $userFeedbackPlaces;

    public function __construct()
    {
        $this->workTimes = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->placeHasServices = new ArrayCollection();
        $this->userFeedbackPlaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?category
    {
        return $this->category;
    }

    public function setCategory(?category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|WorkTime[]
     */
    public function getWorkTimes(): Collection
    {
        return $this->workTimes;
    }

    public function addWorkTime(WorkTime $workTime): self
    {
        if (!$this->workTimes->contains($workTime)) {
            $this->workTimes[] = $workTime;
            $workTime->setPlace($this);
        }

        return $this;
    }

    public function removeWorkTime(WorkTime $workTime): self
    {
        if ($this->workTimes->contains($workTime)) {
            $this->workTimes->removeElement($workTime);
            // set the owning side to null (unless already changed)
            if ($workTime->getPlace() === $this) {
                $workTime->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setPlace($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getPlace() === $this) {
                $image->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PlaceHasService[]
     */
    public function getPlaceHasServices(): Collection
    {
        return $this->placeHasServices;
    }

    public function addPlaceHasService(PlaceHasService $placeHasService): self
    {
        if (!$this->placeHasServices->contains($placeHasService)) {
            $this->placeHasServices[] = $placeHasService;
            $placeHasService->setPlace($this);
        }

        return $this;
    }

    public function removePlaceHasService(PlaceHasService $placeHasService): self
    {
        if ($this->placeHasServices->contains($placeHasService)) {
            $this->placeHasServices->removeElement($placeHasService);
            // set the owning side to null (unless already changed)
            if ($placeHasService->getPlace() === $this) {
                $placeHasService->setPlace(null);
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
            $userFeedbackPlace->setPlace($this);
        }

        return $this;
    }

    public function removeUserFeedbackPlace(UserFeedbackPlace $userFeedbackPlace): self
    {
        if ($this->userFeedbackPlaces->contains($userFeedbackPlace)) {
            $this->userFeedbackPlaces->removeElement($userFeedbackPlace);
            // set the owning side to null (unless already changed)
            if ($userFeedbackPlace->getPlace() === $this) {
                $userFeedbackPlace->setPlace(null);
            }
        }

        return $this;
    }
}
