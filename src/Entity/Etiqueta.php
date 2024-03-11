<?php

namespace App\Entity;

use App\Repository\EtiquetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtiquetaRepository::class)]
class Etiqueta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $nombre = null;

    #[ORM\ManyToMany(targetEntity: Entrada::class, mappedBy: 'etiquetas')]
    private Collection $entradas;

    public function __construct()
    {
        $this->entradas = new ArrayCollection();
    }

    public function getId(): ?int
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

    /**
     * @return Collection<int, Entrada>
     */
    public function getEntradas(): Collection
    {
        return $this->entradas;
    }

    public function addEntrada(Entrada $entrada): static
    {
        if (!$this->entradas->contains($entrada)) {
            $this->entradas->add($entrada);
            $entrada->addEtiqueta($this);
        }

        return $this;
    }

    public function removeEntrada(Entrada $entrada): static
    {
        if ($this->entradas->removeElement($entrada)) {
            $entrada->removeEtiqueta($this);
        }

        return $this;
    }
}
