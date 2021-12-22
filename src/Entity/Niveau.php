<?php

namespace App\Entity;

use App\Repository\NiveauRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NiveauRepository::class)
 */
class Niveau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeNiveau;

    /**
     * @ORM\OneToMany(targetEntity=NearMiss::class, mappedBy="niveau")
     */
    private $nearMisses;

    public function __construct()
    {
        $this->nearMisses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeNiveau(): ?string
    {
        return $this->typeNiveau;
    }

    public function setTypeNiveau(string $typeNiveau): self
    {
        $this->typeNiveau = $typeNiveau;

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
            $nearMiss->setNiveau($this);
        }

        return $this;
    }

    public function removeNearMiss(NearMiss $nearMiss): self
    {
        if ($this->nearMisses->removeElement($nearMiss)) {
            // set the owning side to null (unless already changed)
            if ($nearMiss->getNiveau() === $this) {
                $nearMiss->setNiveau(null);
            }
        }

        return $this;
    }
}
