<?php

/**
 * Book fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class BookFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class BookFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(50, 'books', function (int $i) {
            $ksiazka = new Book();
            $ksiazka->setTitle($this->faker->title);
            $ksiazka->setIsbn(
                $this->faker->isbn13()
            );
            $ksiazka->setRelease_date(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-156324 days', '-1 days')
                )
            );
            $ksiazka->setPage_number($this->faker->numberBetween(15, 2000));
            $ksiazka->setRating($this->faker->numberBetween(0, 10));
            $ksiazka->setDescription($this->faker->text);
            $ksiazka->setStock($this->faker->numberBetween(1, 15));

            $author = $this->getRandomReference('authors');
            $ksiazka->addAuthor($author);

            $category = $this->getRandomReference('categories');
            $ksiazka->addCategory($category);

            return $ksiazka;
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [AuthorFixtures::class, CategoryFixtures::class];
    }
}
