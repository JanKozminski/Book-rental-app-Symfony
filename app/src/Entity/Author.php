<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 1)]
    #[Assert\Choice(['M', 'F'])]
    private ?string $sex = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $birth_date = null;

    #[ORM\Column(length: 255)]
    #[Assert\Country]
    private ?string $country_of_origin = null;

    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: 'autor')]
    private Collection $books;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthdate(\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getCountryoforigin(): ?string
    {
        return $this->country_of_origin;
    }

    public function setCountryoforigin(string $country_of_origin): self
    {
        $this->country_of_origin = $country_of_origin;

        return $this;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBooks(Book $ksiazki): self
    {
        if (!$this->books->contains($ksiazki)) {
            $this->books->add($ksiazki);
        }

        return $this;
    }

    public function removeBooks(Book $ksiazki): self
    {
        $this->books->removeElement($ksiazki);

        return $this;
    }
}
