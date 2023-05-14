<?php
/**
 * Ksiazka fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Ksiazka;
use App\Entity\Autor;
use DateTimeImmutable;

/**
 * Class CategoryyFixtures.
 *
 * @psalm-suppress MissingConstructor
 */
class KsiazkaFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        $this->createMany(50, 'ksiazki', function (int $i) {
            $ksiazka = new Ksiazka();
            $ksiazka->setTytul($this->faker->unique()->title);
            $ksiazka->setIsbn(
                    $this->faker->isbn13()
                );
            $ksiazka->setDataWydania(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-156324 days', '-1 days')
                )
            );
            $ksiazka->setLiczbaStron($this->faker->numberBetween(15, 2000));
            $ksiazka->setOcena($this->faker->numberBetween(0, 10));
            $ksiazka->setOpis($this->faker->text);

            $autor = $this->getRandomReference('autorzy');
            $ksiazka->addAutor($autor);

            $kategoria = $this->getRandomReference('kategorie');
            $ksiazka->addKategoria($kategoria);

            return $ksiazka;
        });

        $this->manager->flush();
    }

public function getDependencies(): array
{
    return [AutorFixtures::class, KsiazkaFixtures::class];
}
}