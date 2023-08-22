<?php

namespace App\Entity;

use App\Repository\DimensionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity(repositoryClass: DimensionRepository::class)]
class Dimension
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups("product")]
    private UuidInterface|string $id;

    #[ORM\Column]
    #[Groups("product")]
    private ?float $largo = null;

    #[ORM\Column]
    #[Groups("product")]
    private ?float $ancho = null;

    #[ORM\Column]
    #[Groups("product")]
    private ?float $alto = null;

    #[ORM\Column(length: 255)]
    #[Groups("product")]
    private ?string $unidad = null;

    public function getId(): UuidInterface|string
    {
        return $this->id;
    }

    public function getLargo(): ?float
    {
        return $this->largo;
    }

    public function setLargo(float $largo): static
    {
        $this->largo = $largo;

        return $this;
    }

    public function getAncho(): ?float
    {
        return $this->ancho;
    }

    public function setAncho(float $ancho): static
    {
        $this->ancho = $ancho;

        return $this;
    }

    public function getAlto(): ?float
    {
        return $this->alto;
    }

    public function setAlto(float $alto): static
    {
        $this->alto = $alto;

        return $this;
    }

    public function getUnidad(): ?string
    {
        return $this->unidad;
    }

    public function setUnidad(string $unidad): static
    {
        $this->unidad = $unidad;

        return $this;
    }
}
