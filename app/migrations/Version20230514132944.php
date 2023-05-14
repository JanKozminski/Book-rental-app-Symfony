<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230514132944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autor (id INT AUTO_INCREMENT NOT NULL, imie_i_nazwisko VARCHAR(255) NOT NULL, plec VARCHAR(1) NOT NULL, data_narodzin DATETIME NOT NULL, kraj_pochodzenia VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE autor_ksiazka (autor_id INT NOT NULL, ksiazka_id INT NOT NULL, INDEX IDX_AAD92B0614D45BBE (autor_id), INDEX IDX_AAD92B06BF07709F (ksiazka_id), PRIMARY KEY(autor_id, ksiazka_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kategoria (id INT AUTO_INCREMENT NOT NULL, nazwa VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE kategoria_ksiazka (kategoria_id INT NOT NULL, ksiazka_id INT NOT NULL, INDEX IDX_8F768340359B0684 (kategoria_id), INDEX IDX_8F768340BF07709F (ksiazka_id), PRIMARY KEY(kategoria_id, ksiazka_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ksiazka (id INT AUTO_INCREMENT NOT NULL, isbn VARCHAR(255) NOT NULL, tytul VARCHAR(255) NOT NULL, ocena INT NOT NULL, liczba_stron INT NOT NULL, opis LONGTEXT NOT NULL, data_wydania DATETIME NOT NULL, ilosc INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE autor_ksiazka ADD CONSTRAINT FK_AAD92B0614D45BBE FOREIGN KEY (autor_id) REFERENCES autor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE autor_ksiazka ADD CONSTRAINT FK_AAD92B06BF07709F FOREIGN KEY (ksiazka_id) REFERENCES ksiazka (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE kategoria_ksiazka ADD CONSTRAINT FK_8F768340359B0684 FOREIGN KEY (kategoria_id) REFERENCES kategoria (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE kategoria_ksiazka ADD CONSTRAINT FK_8F768340BF07709F FOREIGN KEY (ksiazka_id) REFERENCES ksiazka (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE autor_ksiazka DROP FOREIGN KEY FK_AAD92B0614D45BBE');
        $this->addSql('ALTER TABLE autor_ksiazka DROP FOREIGN KEY FK_AAD92B06BF07709F');
        $this->addSql('ALTER TABLE kategoria_ksiazka DROP FOREIGN KEY FK_8F768340359B0684');
        $this->addSql('ALTER TABLE kategoria_ksiazka DROP FOREIGN KEY FK_8F768340BF07709F');
        $this->addSql('DROP TABLE autor');
        $this->addSql('DROP TABLE autor_ksiazka');
        $this->addSql('DROP TABLE kategoria');
        $this->addSql('DROP TABLE kategoria_ksiazka');
        $this->addSql('DROP TABLE ksiazka');
        $this->addSql('DROP TABLE users');
    }
}
