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

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: BookFixtures::class}
     */
    public function getDependencies(): array
    {
        return [BookFixtures::class];
    }
}
