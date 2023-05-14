<?php

namespace App\Entity;

use App\Repository\KategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KategoriaRepository::class)]
class Kategoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nazwa = null;

    #[ORM\ManyToMany(targetEntity: Ksiazka::class, inversedBy: 'kategoria')]
    private Collection $ksiazki;

    public function __construct()
    {
        $this->ksiazki = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNazwa(): ?string
    {
        return $this->nazwa;
    }

    public function setNazwa(string $nazwa): self
    {
        $this->nazwa = $nazwa;

        return $this;
    }

    /**
     * @return Collection<int, Ksiazka>
     */
    public function getKsiazki(): Collection
    {
        return $this->ksiazki;
    }

    public function addKsiazki(Ksiazka $ksiazki): self
    {
        if (!$this->ksiazki->contains($ksiazki)) {
            $this->ksiazki->add($ksiazki);
        }

        return $this;
    }

    public function removeKsiazki(Ksiazka $ksiazki): self
    {
        $this->ksiazki->removeElement($ksiazki);

        return $this;
    }
}
