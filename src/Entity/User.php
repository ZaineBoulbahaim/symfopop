<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

// Definim que aquesta classe és una entitat de Doctrine
#[ORM\Entity(repositoryClass: UserRepository::class)]
// Constraint per assegurar que l'email és únic a la base de dades
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Clau primària autoincremental
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Email de l'usuari: únic, màxim 180 caràcters
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    // Rols de l'usuari: emmagatzemats en format JSON a la BD
    // Per defecte tots els usuaris tenen ROLE_USER
    #[ORM\Column]
    private array $roles = [];

    // Contrasenya hashejada de l'usuari
    #[ORM\Column]
    private ?string $password = null;

    // Nom de l'usuari: màxim 255 caràcters, obligatori
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // Relació OneToMany: un usuari pot tenir molts productes
    // mappedBy indica que Product és el propietari de la relació (té el ManyToOne)
    // orphanRemoval: si s'elimina l'usuari, s'eliminen els seus productes
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Product::class, orphanRemoval: true)]
    private Collection $products;

    // Constructor: inicialitza la col·lecció de productes buida
    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    // --- GETTERS I SETTERS ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Identificador visual de l'usuari (el seu email)
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Retorna els rols de l'usuari
     * Sempre inclou ROLE_USER com a mínim
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Tot usuari té com a mínim ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Retorna la contrasenya hashejada
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Serialitza l'objecte per a la sessió
     * Usa hash CRC32C per no guardar la contrasenya real a la sessió
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);
        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, s'eliminarà en Symfony 8
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Retorna la col·lecció de productes de l'usuari
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    // Afegeix un producte a la col·lecció de l'usuari
    public function addProduct(Product $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            // Assigna aquest usuari com a propietari del producte
            $product->setOwner($this);
        }
        return $this;
    }

    // Elimina un producte de la col·lecció de l'usuari
    public function removeProduct(Product $product): static
    {
        if ($this->products->removeElement($product)) {
            // Si el producte tenia aquest usuari com a owner, el posa a null
            if ($product->getOwner() === $this) {
                $product->setOwner(null);
            }
        }
        return $this;
    }
}