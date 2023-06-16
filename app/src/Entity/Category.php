<?php
/**
 * Category entity.
 */

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Category.
 *
 * @psalm-suppress MissingConstructor
 */
#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
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
     * Books.
     *
     * @var Collection Books
     */
    #[ORM\ManyToMany(targetEntity: Book::class, mappedBy: 'category')]
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
     * @return Collection<int, Book>
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    /**
     * Add action.
     *
     * @param Book $book Book
     *
     * @return Category Books
     */
    public function addBooks(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books->add($book);
        }

        return $this;
    }

    /**
     * Remove action.
     *
     * @param Book $book Book
     *
     * @return Category Books
     */
    public function removeBooks(Book $book): self
    {
        $this->books->removeElement($book);

        return $this;
    }
}
