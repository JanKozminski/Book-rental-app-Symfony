<?php
/**
 * Category fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;

/**
 * Class CategoryFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class CategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(10, 'categories', function (int $i) {
            $category = new Category();
            $category->setName($this->faker->randomElement($array = ['Horror', 'Criminal', 'Fantasy', 'Thriller', 'Drama', 'Biography', 'Customary', 'Romance', 'Fairytale']));

            return $category;
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [BookFixtures::class];
    }
}
