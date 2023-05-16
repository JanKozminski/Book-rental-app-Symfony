<?php
/**
 * Autor fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Autor;
use DateTimeImmutable;

/**
 * Class AutorFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class AutorFixtures extends AbstractBaseFixtures
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
            $autor = new Autor();
            $sex = $this->faker->randomElement( $array = ['male','female']);
            $autor->setName($this->faker->unique()->name($sex));
            if($sex=='male') $autor->setSex('M');
            if($sex=='female') $autor->setSex('F');
            $autor->setBirthdate(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-156324 days', '-1 days'))
            );
            $autor->setCountryoforigin($this->faker->country);
            return $autor;
        });

        $this->manager->flush();
    }
    public function getDependencies(): array
    {
        return [BookFixtures::class];
    }
}
