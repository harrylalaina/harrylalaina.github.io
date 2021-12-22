<?php

namespace App\Entity;

use App\Repository\YearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=YearRepository::class)
 */
class Year
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $debut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fin;

    /**
     * @ORM\OneToMany(targetEntity=NearMiss::class, mappedBy="year")
     */
    private $nearMisses;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    public function __construct()
    {
        $this->nearMisses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

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
            $nearMiss->setYear($this);
        }

        return $this;
    }

    public function removeNearMiss(NearMiss $nearMiss): self
    {
        if ($this->nearMisses->removeElement($nearMiss)) {
            // set the owning side to null (unless already changed)
            if ($nearMiss->getYear() === $this) {
                $nearMiss->setYear(null);
            }
        }

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }
}
