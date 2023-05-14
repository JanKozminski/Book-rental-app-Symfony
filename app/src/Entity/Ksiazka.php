<?php

namespace App\Entity;

use App\Repository\KsiazkaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: KsiazkaRepository::class)]
class Ksiazka
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Isbn(
        type: Assert\Isbn::ISBN_13,
        message: 'Ta wartosc nie jest zgodna z ISBN13.',
    )]
    private ?string $isbn = null;

    #[ORM\Column(length: 255)]
    private ?string $tytul = null;

    #[ORM\Column]
    #[Assert\Range(
        notInRangeMessage: 'Ocena nie miesci sie w zakresie od {{ min }} do {{ max }}.',
        min: 0,
        max: 10,
    )]
    private ?int $ocena = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $liczba_stron = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $opis = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $data_wydania = null;

    #[ORM\Column]
    private ?int $ilosc = null;

    #[ORM\ManyToMany(targetEntity: Kategoria::class, mappedBy: 'ksiazki')]
    private Collection $kategoria;

    #[ORM\ManyToMany(targetEntity: Autor::class, mappedBy: 'ksiazki')]
    private Collection $autor;

    public function __construct()
    {
        $this->kategoria = new ArrayCollection();
        $this->autor = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTytul(): ?string
    {
        return $this->tytul;
    }

    public function setTytul(string $tytul): self
    {
        $this->tytul = $tytul;

        return $this;
    }

    public function getOcena(): ?int
    {
        return $this->ocena;
    }

    public function setOcena(int $ocena): self
    {
        $this->ocena = $ocena;

        return $this;
    }

    public function getLiczbaStron(): ?int
    {
        return $this->liczba_stron;
    }

    public function setLiczbaStron(int $liczba_stron): self
    {
        $this->liczba_stron = $liczba_stron;

        return $this;
    }

    public function getOpis(): ?string
    {
        return $this->opis;
    }

    public function setOpis(string $opis): self
    {
        $this->opis = $opis;

        return $this;
    }

    public function getDataWydania(): ?\DateTimeInterface
    {
        return $this->data_wydania;
    }

    public function setDataWydania(\DateTimeInterface $data_wydania): self
    {
        $this->data_wydania = $data_wydania;

        return $this;
    }

    public function getIlosc(): ?int
    {
        return $this->ilosc;
    }

    public function setIlosc(int $ilosc): self
    {
        $this->ilosc = $ilosc;

        return $this;
    }

    /**
     * @return Collection<int, Kategoria>
     */
    public function getKategoria(): Collection
    {
        return $this->kategoria;
    }

    public function addKategoria(Kategoria $kategorium): self
    {
        if (!$this->kategoria->contains($kategorium)) {
            $this->kategoria->add($kategorium);
            $kategorium->addKsiazki($this);
        }

        return $this;
    }

    public function removeKategoria(Kategoria $kategorium): self
    {
        if ($this->kategoria->removeElement($kategorium)) {
            $kategorium->removeKsiazki($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Autor>
     */
    public function getAutor(): Collection
    {
        return $this->autor;
    }

    public function addAutor(Autor $autor): self
    {
        if (!$this->autor->contains($autor)) {
            $this->autor->add($autor);
            $autor->addKsiazki($this);
        }

        return $this;
    }

    public function removeAutor(Autor $autor): self
    {
        if ($this->autor->removeElement($autor)) {
            $autor->removeKsiazki($this);
        }

        return $this;
    }
}
