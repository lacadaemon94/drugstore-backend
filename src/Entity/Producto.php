<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductoRepository::class)]
class Producto
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups("product")]
    private UuidInterface|string $id;

    #[ORM\Column(length: 255)]
    #[Groups("product")]
    private ?string $nombre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups("product")]
    private ?string $descripcion = null;

    #[ORM\ManyToOne(cascade: ["persist"])]
    #[Groups("product")]
    private ?Envase $envase_id = null;

    #[ORM\ManyToMany(targetEntity: Ingrediente::class)]
    #[Groups("product")]
    private Collection $ingredientes;

    // #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    // #[Groups("product")]
    // private ?\DateTimeInterface $expiracion = null;

    public function __construct()
    {
        $this->ingredientes = new ArrayCollection();
    }

    public function getId(): UuidInterface|string
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getEnvaseId(): ?Envase
    {
        return $this->envase_id;
    }

    public function setEnvaseId(?Envase $envase_id): static
    {
        $this->envase_id = $envase_id;

        return $this;
    }

    /**
     * @return Collection<int, Ingrediente>
     */
    public function getIngredientes(): Collection
    {
        return $this->ingredientes;
    }

    public function addIngrediente(Ingrediente $ingrediente): static
    {
        if (!$this->ingredientes->contains($ingrediente)) {
            $this->ingredientes->add($ingrediente);
        }

        return $this;
    }

    public function removeIngrediente(Ingrediente $ingrediente): static
    {
        $this->ingredientes->removeElement($ingrediente);

        return $this;
    }
}
