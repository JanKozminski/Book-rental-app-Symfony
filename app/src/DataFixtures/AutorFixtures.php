<?php
/**
 * Autor fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Ksiazka;
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
        $this->createMany(20, 'autorzy', function (int $i) {
            $autor = new Autor();
            $plec = $this->faker->randomElement( $array = ['male','female']);
            $autor->setImieINazwisko($this->faker->unique()->name($plec));
            if($plec=='male') $autor->setPlec('M');
            if($plec=='female') $autor->setPlec('K');
            $autor->setDataNarodzin(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-156324 days', '-1 days'))
            );
            $autor->setKrajPochodzenia($this->faker->country);
            $ksiazka = $this->getRandomReference('ksiazki');
            $autor->addKsiazki($ksiazka);
            return $autor;
        });

        $this->manager->flush();
    }
    public function getDependencies(): array
    {
        return [KsiazkaFixtures::class];
    }
}
