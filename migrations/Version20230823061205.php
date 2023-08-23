<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230823061205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entreprise (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, raison_sociale VARCHAR(255) DEFAULT NULL, siege VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, annee VARCHAR(255) DEFAULT NULL, domaine CLOB DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, rc VARCHAR(255) DEFAULT NULL, cc VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, valid BOOLEAN DEFAULT NULL, created_at DATETIME DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE entreprise');
    }
}
