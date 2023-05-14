<?php

namespace App\Entity;

use App\Repository\AutorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AutorRepository::class)]
class Autor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $imie_i_nazwisko = null;

    #[ORM\Column(length: 1)]
    #[Assert\Choice(['M', 'K'])]
    private ?string $plec = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $data_narodzin = null;

    #[ORM\Column(length: 255)]
    #[Assert\Country]
    private ?string $kraj_pochodzenia = null;

    #[ORM\ManyToMany(targetEntity: Ksiazka::class, inversedBy: 'autor')]
    private Collection $ksiazki;

    public function __construct()
    {
        $this->ksiazki = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImieINazwisko(): ?string
    {
        return $this->imie_i_nazwisko;
    }

    public function setImieINazwisko(string $imie_i_nazwisko): self
    {
        $this->imie_i_nazwisko = $imie_i_nazwisko;

        return $this;
    }

    public function getPlec(): ?string
    {
        return $this->plec;
    }

    public function setPlec(string $plec): self
    {
        $this->plec = $plec;

        return $this;
    }

    public function getDataNarodzin(): ?\DateTimeInterface
    {
        return $this->data_narodzin;
    }

    public function setDataNarodzin(\DateTimeInterface $data_narodzin): self
    {
        $this->data_narodzin = $data_narodzin;

        return $this;
    }

    public function getKrajPochodzenia(): ?string
    {
        return $this->kraj_pochodzenia;
    }

    public function setKrajPochodzenia(string $kraj_pochodzenia): self
    {
        $this->kraj_pochodzenia = $kraj_pochodzenia;

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
