<?php

namespace App\Entity;

use App\Repository\InventarioRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: InventarioRepository::class)]
#[ORM\Table(name: "inventario")]
#[ORM\UniqueConstraint(name: "producto_expiracion_idx", columns: ["tipo_id_id", "producto_id_id", "expiracion"])]

class Inventario
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface|string $id;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tipo $tipo_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Producto $producto_id = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    private ?\DateTimeInterface $expiracion = null;

    public function getId(): UuidInterface|string
    {
        return $this->id;
    }

    public function getTipoId(): ?Tipo
    {
        return $this->tipo_id;
    }

    public function setTipoId(?Tipo $tipo_id): static
    {
        $this->tipo_id = $tipo_id;

        return $this;
    }

    public function getProductoId(): ?Producto
    {
        return $this->producto_id;
    }

    public function setProductoId(?Producto $producto_id): static
    {
        $this->producto_id = $producto_id;

        return $this;
    }

    public function getCantidad(): ?int
    {
        return $this->cantidad;
    }

    public function setCantidad(int $cantidad): static
    {
        $this->cantidad = $cantidad;

        return $this;
    }

    public function getExpiracion(): ?\DateTimeInterface
    {
        return $this->expiracion;
    }

    public function setExpiracion(?\DateTimeInterface $expiracion): static
    {
        $this->expiracion = $expiracion;

        return $this;
    }
}
