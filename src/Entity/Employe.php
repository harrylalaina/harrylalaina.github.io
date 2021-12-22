<?php

namespace App\Entity;

use App\Repository\EmployeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployeRepository::class)
 */
class Employe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomResponsable;

    /**
     * @ORM\OneToMany(targetEntity=NearMiss::class, mappedBy="employe", cascade={"persist", "remove"})
     */
    private $nearMisses;

    /**
     * @ORM\ManyToOne(targetEntity=Service::class, inversedBy="employes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

    public function __construct()
    {
        $this->nearMisses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNomResponsable(): ?string
    {
        return $this->nomResponsable;
    }

    public function setNomResponsable(?string $nomResponsable): self
    {
        $this->nomResponsable = $nomResponsable;

        return $this;
    }

    /**
     * @return Collection|NearMiss[]
     */
    public function getNearMisses(): Collection
    {
        return $this->nearMisses;
    }

    public function addNearMiss(NearMiss $nearMiss): self
    {
        if (!$this->nearMisses->contains($nearMiss)) {
            $this->nearMisses[] = $nearMiss;
            $nearMiss->setEmploye($this);
        }

        return $this;
    }

    public function removeNearMiss(NearMiss $nearMiss): self
    {
        if ($this->nearMisses->removeElement($nearMiss)) {
            // set the owning side to null (unless already changed)
            if ($nearMiss->getEmploye() === $this) {
                $nearMiss->setEmploye(null);
            }
        }

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }
}
