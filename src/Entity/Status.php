<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 */
class Status
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
    private $typeStatus;

    /**
     * @ORM\OneToMany(targetEntity=NearMiss::class, mappedBy="status")
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

    public function getTypeStatus(): ?string
    {
        return $this->typeStatus;
    }

    public function setTypeStatus(string $typeStatus): self
    {
        $this->typeStatus = $typeStatus;

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
            $nearMiss->setStatus($this);
        }

        return $this;
    }

    public function removeNearMiss(NearMiss $nearMiss): self
    {
        if ($this->nearMisses->removeElement($nearMiss)) {
            // set the owning side to null (unless already changed)
            if ($nearMiss->getStatus() === $this) {
                $nearMiss->setStatus(null);
            }
        }

        return $this;
    }
}
