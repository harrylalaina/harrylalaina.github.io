<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NearMissRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=NearMissRepository::class)
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="near_miss", indexes={@ORM\Index(columns={"titre", "description"}, flags={"fulltext"})})
 * @Vich\Uploadable
 */
class NearMiss
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le titre ne peut pas être vide")
     * @Assert\Length(min=10)
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veillez décrire l'incident")
     * @Assert\Length(min=10)
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez décrire l'action immédiate que vous avez prise")
     */
    private $action_immediate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $niveau_risque;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $tacheAffecte;

    /**
     * @ORM\ManyToOne(targetEntity=Employe::class, inversedBy="nearMisses", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $employe;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default": "CURRENT_TIMESTAMP"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $actionPossible;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $responsable;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $actionPossible01;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $responsable01;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $actionPossible02;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $responsable02;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="nearMisses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $support;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="nearMisses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $actionPrevention;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $week;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="preuve_image", fileNameProperty="preuve")
     * 
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preuve;

    /**
     * @ORM\ManyToOne(targetEntity=Niveau::class, inversedBy="nearMisses")
     */
    private $niveau;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $closedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Year::class, inversedBy="nearMisses")
     */
    private $year;



    public function __construct()
    {
        $this->mesures = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getActionImmediate(): ?string
    {
        return $this->action_immediate;
    }

    public function setActionImmediate(?string $action_immediate): self
    {
        $this->action_immediate = $action_immediate;

        return $this;
    }

    public function getNiveauRisque(): ?string
    {
        return $this->niveau_risque;
    }

    public function setNiveauRisque(?string $niveau_risque): self
    {
        $this->niveau_risque = $niveau_risque;

        return $this;
    }

    public function getTacheAffecte()
    {
        return $this->tacheAffecte;
    }

    public function setTacheAffecte($tacheAffecte): self
    {
        $this->tacheAffecte = $tacheAffecte;

        return $this;
    }

    public function getActionPossible(): ?string
    {
        return $this->actionPossible;
    }

    public function setActionPossible(?string $actionPossible): self
    {
        $this->actionPossible = $actionPossible;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(?string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getEmploye(): ?Employe
    {
        return $this->employe;
    }

    public function setEmploye(?Employe $employe): self
    {
        $this->employe = $employe;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps()
    {
        $d = new DateTimeImmutable();
        $date = $d->format('W');
        $countDate = $d->format('j F Y');
        if ($this->getClosedAt() == null) {
            if ($this->getNiveau()->getTypeNiveau() == "Niveau 1") {
                $res = date('j F Y', strtotime("$countDate + 14 day"));
                $this->setClosedAt(new DateTimeImmutable($res));
            }
            if ($this->getNiveau()->getTypeNiveau() == "Niveau 2") {
                $res = date('j F y', strtotime("$countDate + 62 day"));
                $this->setClosedAt(new DateTimeImmutable($res));
            }
            if ($this->getNiveau()->getTypeNiveau() == "Niveau 3") {
                $res = date('j F y', strtotime("$countDate + 365 day"));
                $this->setClosedAt(new DateTimeImmutable($res));
            }
        }
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable());
        }
        if ($this->getWeek() === null) {
            $this->setWeek($date);
        }
        $this->setUpdatedAt(new \DateTimeImmutable());
    }

    public function getActionPossible01(): ?string
    {
        return $this->actionPossible01;
    }

    public function setActionPossible01(?string $actionPossible01): self
    {
        $this->actionPossible01 = $actionPossible01;

        return $this;
    }

    public function getResponsable01(): ?string
    {
        return $this->responsable01;
    }

    public function setResponsable01(?string $responsable01): self
    {
        $this->responsable01 = $responsable01;

        return $this;
    }

    public function getActionPossible02(): ?string
    {
        return $this->actionPossible02;
    }

    public function setActionPossible02(?string $actionPossible02): self
    {
        $this->actionPossible02 = $actionPossible02;

        return $this;
    }

    public function getResponsable02(): ?string
    {
        return $this->responsable02;
    }

    public function setResponsable02(?string $responsable02): self
    {
        $this->responsable02 = $responsable02;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSupport(): ?string
    {
        return $this->support;
    }

    public function setSupport(?string $support): self
    {
        $this->support = $support;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getActionPrevention(): ?string
    {
        return $this->actionPrevention;
    }

    public function setActionPrevention(?string $actionPrevention): self
    {
        $this->actionPrevention = $actionPrevention;

        return $this;
    }

    public function getWeek(): ?int
    {
        return $this->week;
    }

    public function setWeek(?int $week): self
    {
        $this->week = $week;

        return $this;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->SetUpdatedAt(new \DateTimeImmutable);
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getPreuve(): ?string
    {
        return $this->preuve;
    }

    public function setPreuve(?string $preuve): self
    {
        $this->preuve = $preuve;

        return $this;
    }

    public function getNiveau(): ?Niveau
    {
        return $this->niveau;
    }

    public function setNiveau(?Niveau $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getClosedAt(): ?\DateTimeImmutable
    {
        return $this->closedAt;
    }

    public function setClosedAt(?\DateTimeImmutable $closedAt): self
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    public function getYear(): ?Year
    {
        return $this->year;
    }

    public function setYear(?Year $year): self
    {
        $this->year = $year;

        return $this;
    }
}
