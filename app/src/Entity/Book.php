<?php

/**
 * Book entity.
 */

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Book.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Isbn.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Isbn(
        type: Assert\Isbn::ISBN_13,
        message: 'book.isbn.invalid',
    )]
    private ?string $isbn = null;

    /**
     * Title.
     */
    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255, maxMessage : 'book.title.too_long')]
    private ?string $title = null;

    /**
     * Rating.
     */
    #[ORM\Column]
    #[Assert\Range(
        notInRangeMessage: 'book.rating.out_of_range',
        min: 0,
        max: 10,
    )]
    private ?int $rating = null;

    /**
     * Page number.
     */
    #[ORM\Column]
    #[Assert\Positive]
    private ?int $pageNumber = null;

    /**
     * Description.
     */
    #[ORM\Column(type: Types::TEXT, length: 500)]
    #[Assert\Length(max: 500, maxMessage: 'book.description.too_long')]
    private ?string $description = null;

    /**
     * Release date.
     *
     * @var \DateTimeImmutable|null
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $releaseDate = null;

    /**
     * Stock.
     */
    #[ORM\Column]
    #[Assert\Type(type: 'integer', message: 'This field should be a integer type')]
    private ?int $stock = null;

    /**
     * Categories.
     *
     * @var Collection object|null
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'books')]
    private Collection $category;
    /**
     * Authors.
     *
     * @var Collection object|null
     */
    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    private Collection $author;

    /**
     * Constructor for collection Categories and Authors.
     */
    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->author = new ArrayCollection();
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
     * Getter for Isbn.
     *
     * @return string|null Isbn
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * Setter for isbn.
     *
     * @param string|null $isbn Isbn
     */
    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    /**
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string|null $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for rating.
     *
     * @return int|null Rating
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * Setter for rating.
     *
     * @param int|null $rating Rating
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * Getter for pageNumber.
     *
     * @return int|null Page number
     */
    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    /**
     * Setter for pageNumber.
     *
     * @param int|null $pageNumber Page Number
     */
    public function setPageNumber(int $pageNumber): void
    {
        $this->pageNumber = $pageNumber;
    }

    /**
     * Getter for description.
     *
     * @return string|null Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for description.
     *
     * @param string|null $description Description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Getter for releaseDate.
     *
     * @return \DateTimeImmutable|null Release Date
     */
    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    /**
     * Setter for releaseDate.
     *
     * @param \DateTimeImmutable|null $releaseDate Release Date
     */
    public function setReleaseDate(\DateTimeInterface $releaseDate): void
    {
        $this->releaseDate = $releaseDate;
    }

    /**
     * Getter for stock.
     *
     * @return int|null Stock
     */
    public function getStock(): ?int
    {
        return $this->stock;
    }

    /**
     * Setter for stock.
     *
     * @param int|null $stock Stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    /**
     * Add action.
     *
     * @param Category $kategorium Category
     *
     * @return Author Books
     */
    public function addCategory(Category $kategorium): self
    {
        if (!$this->category->contains($kategorium)) {
            $this->category->add($kategorium);
            // $kategorium->addBooks($this);
        }

        return $this;
    }

    /**
     * Remove action.
     *
     * @param Category $kategorium Category
     *
     * @return Book Books
     */
    public function removeCategory(Category $kategorium): self
    {
        if ($this->category->removeElement($kategorium)) {
            $kategorium->removeBooks($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthor(): Collection
    {
        return $this->author;
    }

    /**
     * Add action.
     *
     * @param Author $author Author
     *
     * @return Book Books
     */
    public function addAuthor(Author $author): self
    {
        if (!$this->author->contains($author)) {
            $this->author->add($author);
            $author->addBooks($this);
        }

        return $this;
    }

    /**
     * Remove action.
     *
     * @param Author $author Author
     *
     * @return Book Books
     */
    public function removeAuthor(Author $author): self
    {
        if ($this->author->removeElement($author)) {
            $author->removeBooks($this);
        }

        return $this;
    }
}
