<?php

namespace App\Entity;

use App\Repository\EnvaseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: EnvaseRepository::class)]
class Envase
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

    #[ORM\Column(length: 255)]
    #[Groups("product")]
    private ?string $material = null;

    #[ORM\Column]
    #[Groups("product")]
    private ?float $volumen = null;

    #[ORM\Column(length: 255)]
    #[Groups("product")]
    private ?string $unidadVol = null;

    #[ORM\ManyToOne(cascade: ["persist"])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("product")]
    private ?Dimension $dimension_id = null;

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

    public function getMaterial(): ?string
    {
        return $this->material;
    }

    public function setMaterial(string $material): static
    {
        $this->material = $material;

        return $this;
    }

    public function getVolumen(): ?float
    {
        return $this->volumen;
    }

    public function setVolumen(float $volumen): static
    {
        $this->volumen = $volumen;

        return $this;
    }

    public function getUnidadVol(): ?string
    {
        return $this->unidadVol;
    }

    public function setUnidadVol(string $unidadVol): static
    {
        $this->unidadVol = $unidadVol;

        return $this;
    }

    public function getDimensionId(): ?Dimension
    {
        return $this->dimension_id;
    }

    public function setDimensionId(?Dimension $dimension_id): static
    {
        $this->dimension_id = $dimension_id;

        return $this;
    }
}
