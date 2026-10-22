<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261015093517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Catégorie des véhicules, prix des options, mapping des tables de location';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location_option RENAME INDEX id_option TO IDX_11F488B27CB1B55D');
        $this->addSql('ALTER TABLE location_vehicule RENAME INDEX id_client TO IDX_F87ADDFFE173B1B8');
        $this->addSql('ALTER TABLE location_vehicule RENAME INDEX id_vehicule TO IDX_F87ADDFF79F41388');
        $this->addSql('ALTER TABLE `option` ADD prix NUMERIC(8, 2) NOT NULL');
        // Les véhicules déjà saisis sont rattachés à la première catégorie avant de passer la colonne en NOT NULL
        $this->addSql('ALTER TABLE vehicule ADD categorie_id INT DEFAULT NULL');
        $this->addSql('UPDATE vehicule SET categorie_id = (SELECT MIN(id) FROM categorie)');
        $this->addSql('ALTER TABLE vehicule MODIFY categorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_292FFF1DBE73422E ON vehicule (immatriculation)');
        $this->addSql('CREATE INDEX IDX_292FFF1DBCF5E72D ON vehicule (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location_option RENAME INDEX idx_11f488b27cb1b55d TO id_option');
        $this->addSql('ALTER TABLE location_vehicule RENAME INDEX idx_f87addffe173b1b8 TO id_client');
        $this->addSql('ALTER TABLE location_vehicule RENAME INDEX idx_f87addff79f41388 TO id_vehicule');
        $this->addSql('ALTER TABLE `option` DROP prix');
        $this->addSql('ALTER TABLE vehicule DROP FOREIGN KEY FK_292FFF1DBCF5E72D');
        $this->addSql('DROP INDEX UNIQ_292FFF1DBE73422E ON vehicule');
        $this->addSql('DROP INDEX IDX_292FFF1DBCF5E72D ON vehicule');
        $this->addSql('ALTER TABLE vehicule DROP categorie_id');
    }
}
