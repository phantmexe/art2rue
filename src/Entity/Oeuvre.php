<?php

namespace App\Entity;

use App\Repository\OeuvreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OeuvreRepository::class)]
class Oeuvre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?bool $estDetruite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nomFichier = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    /**
     * @var Collection<int, Technique>
     */
    #[ORM\ManyToMany(targetEntity: Technique::class, inversedBy: 'oeuvres')]
    private Collection $techniques;

    public function __construct()
    {
        $this->techniques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function isEstDetruite(): ?bool
    {
        return $this->estDetruite;
    }

    public function setEstDetruite(?bool $estDetruite): static
    {
        $this->estDetruite = $estDetruite;

        return $this;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(?string $nomFichier): static
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * @return Collection<int, Technique>
     */
    public function getTechniques(): Collection
    {
        return $this->techniques;
    }

    public function addTechnique(Technique $technique): static
    {
        if (!$this->techniques->contains($technique)) {
            $this->techniques->add($technique);
        }

        return $this;
    }

    public function removeTechnique(Technique $technique): static
    {
        $this->techniques->removeElement($technique);

        return $this;
    }
}
