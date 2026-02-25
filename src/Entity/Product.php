<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

// Definim que aquesta classe és una entitat de Doctrine
// i que el seu repositori és ProductRepository
#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    // Clau primària autoincremental
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Títol del producte: màxim 255 caràcters, obligatori
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'El títol no pot estar buit')]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'El títol ha de tenir com a mínim {{ limit }} caràcters',
        maxMessage: 'El títol no pot superar els {{ limit }} caràcters'
    )]
    private ?string $title = null;

    // Descripció del producte: tipus text (sense límit), obligatori
    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'La descripció no pot estar buida')]
    #[Assert\Length(
        min: 10,
        minMessage: 'La descripció ha de tenir com a mínim {{ limit }} caràcters'
    )]
    private ?string $description = null;

    // Preu del producte: decimal amb 10 dígits totals i 2 decimals, obligatori
    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    #[Assert\NotBlank(message: 'El preu no pot estar buit')]
    #[Assert\Positive(message: 'El preu ha de ser un valor positiu')]
    private ?string $price = null;

    // URL de la imatge del producte: màxim 500 caràcters, opcional (nullable)
    #[ORM\Column(length: 500, nullable: true)]
    #[Assert\Url(message: 'La URL de la imatge no és vàlida')]
    private ?string $image = null;

    // Data de creació del producte: obligatori
    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    // Relació ManyToOne: molts productes poden pertànyer a un usuari (owner)
    // Si s'esborra l'usuari, s'esborren els seus productes (CASCADE)
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $owner = null;

    // --- GETTERS I SETTERS ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;
        return $this;
    }
}