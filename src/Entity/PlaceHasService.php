<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PlaceHasServiceRepository")
 */
class PlaceHasService
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\service")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="placeHasServices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $place;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=2)
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getService(): ?service
    {
        return $this->service;
    }

    public function setService(?service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getPlace(): ?place
    {
        return $this->place;
    }

    public function setPlace(?place $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }
}
