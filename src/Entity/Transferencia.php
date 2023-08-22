<?php

namespace App\Entity;

use App\Repository\TransferenciaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: TransferenciaRepository::class)]
class Transferencia
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface|string $id;


    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Producto $producto_id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tipo $origen = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tipo $destino = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fechaRealizada = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expiracion = null;

    #[ORM\Column]
    private ?int $cantidad = null;

    public function getId(): UuidInterface|string
    {
        return $this->id;
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

    public function getOrigen(): ?Tipo
    {
        return $this->origen;
    }

    public function setOrigen(?Tipo $origen): static
    {
        $this->origen = $origen;

        return $this;
    }

    public function getDestino(): ?Tipo
    {
        return $this->destino;
    }

    public function setDestino(?Tipo $destino): static
    {
        $this->destino = $destino;

        return $this;
    }

    public function getFechaRealizada(): ?\DateTimeInterface
    {
        return $this->fechaRealizada;
    }

    public function setFechaRealizada(\DateTimeInterface $fechaRealizada): static
    {
        $this->fechaRealizada = $fechaRealizada;

        return $this;
    }

    public function getExpiracion(): ?\DateTimeInterface
    {
        return $this->expiracion;

        return $this;
    }

    public function setExpiracion(\DateTimeInterface $expiracion): static
    {
        $this->expiracion = $expiracion;

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
}
