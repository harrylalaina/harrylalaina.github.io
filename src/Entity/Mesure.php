<?php

namespace App\Entity;

use App\Repository\MesureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MesureRepository::class)
 */
class Mesure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $actionPossible;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $responsable;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActionPossible(): ?string
    {
        return $this->actionPossible;
    }

    public function setActionPossible(string $actionPossible): self
    {
        $this->actionPossible = $actionPossible;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }
}
