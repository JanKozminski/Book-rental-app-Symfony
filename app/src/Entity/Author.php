<?php

/**
 * Author entity.
 */

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Author.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Name.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, maxMessage: 'Field should have maximum of {{ limit }} signs')]
    private ?string $name = null;

    /**
     * Sex.
     */
    #[ORM\Column(length: 1)]
    #[Assert\Choice(['M', 'F'])]
    private ?string $sex = null;
    /**
     * BirthDate.
     *
     * @var \DateTimeImmutable|null
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;

    /**
     * CountryOfOrigin.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Country]
    private ?string $countryOfOrigin = null;
    /**
     * Books.
     *
     * @var Collection Books
     */
    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: 'author')]
    private Collection $books;

    /**
     * Constructor for collection Books.
     */
    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for name.
     *
     * @param string|null $name Name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for sex.
     *
     * @return string|null Sex
     */
    public function getSex(): ?string
    {
        return $this->sex;
    }

    /**
     * Setter for sex.
     *
     * @param string|null $sex Sex
     */
    public function setSex(string $sex): void
    {
        $this->sex = $sex;
    }

    /**
     * Getter for birthDate.
     *
     * @return \DateTimeImmutable|null Birth Date
     */
    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    /**
     * Setter for birthDate.
     *
     * @param \DateTimeImmutable|null $birthDate Birth Date
     */
    public function setBirthDate(\DateTimeInterface $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * Getter for countryOfOrigin.
     *
     * @return string|null Country of origin
     */
    public function getCountryOfOrigin(): ?string
    {
        return $this->countryOfOrigin;
    }

    /**
     * Setter for countryOfOrigin.
     *
     * @param string|null $countryOfOrigin Country of origin
     */
    public function setCountryOfOrigin(string $countryOfOrigin): void
    {
        $this->countryOfOrigin = $countryOfOrigin;
    }

    /**
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    /**
     * Add action.
     *
     * @param Book $books Books
     *
     * @return Author Books
     */
    public function addBooks(Book $books): self
    {
        if (!$this->books->contains($books)) {
            $this->books->add($books);
        }

        return $this;
    }

    /**
     * Remove action.
     *
     * @param Book $books Books
     *
     * @return Author Books
     */
    public function removeBooks(Book $books): self
    {
        $this->books->removeElement($books);

        return $this;
    }
}
