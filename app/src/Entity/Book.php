<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Isbn(
        type: Assert\Isbn::ISBN_13,
        message: 'This value isn`t right with ISBN13.',
    )]
    private ?string $isbn = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    #[Assert\Range(
        notInRangeMessage: 'Rating doesn`t fit between  {{ min }} and {{ max }}.',
        min: 0,
        max: 10,
    )]
    private ?int $rating = null;

    #[ORM\Column]
    #[Assert\Positive]
    private ?int $pageNumber = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'books')]
    private Collection $category;

    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    private Collection $author;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->author = new ArrayCollection();
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPageNumber(): ?int
    {
        return $this->pageNumber;
    }

    public function setPageNumber(int $pageNumber): self
    {
        $this->pageNumber = $pageNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $release_date): self
    {
        $this->releaseDate = $release_date;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $kategorium): self
    {
        if (!$this->category->contains($kategorium)) {
            $this->category->add($kategorium);
            // $kategorium->addBooks($this);
        }

        return $this;
    }

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

    public function addAuthor(Author $author): self
    {
        if (!$this->author->contains($author)) {
            $this->author->add($author);
            $author->addBooks($this);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        if ($this->author->removeElement($author)) {
            $author->removeBooks($this);
        }

        return $this;
    }

}
