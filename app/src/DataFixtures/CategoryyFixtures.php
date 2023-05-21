<?php
/**
 * Categoryy fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Categoryy;
use DateTimeImmutable;

/**
 * Class CategoryyFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class CategoryyFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(20, 'categoriess', function (int $i) {
            $category = new Categoryy();
            $category->setTitle($this->faker->unique()->word);
            $category->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $category->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            return $category;
        });

        $this->manager->flush();
    }
}