<?php
/**
 * Author fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Author;

/**
 * Class AuthorFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class AuthorFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(20, 'authors', function (int $i) {
            $autor = new Author();
            $sex = $this->faker->randomElement($array = ['male', 'female']);
            $autor->setName($this->faker->unique()->name($sex));
            if ('male' == $sex) {
                $autor->setSex('M');
            }
            if ('female' == $sex) {
                $autor->setSex('F');
            }
            $autor->setBirthDate(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-156324 days', '-1 days')
                )
            );
            $autor->setCountryOfOrigin($this->faker->country);

            return $autor;
        });

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [BookFixtures::class];
    }
}
