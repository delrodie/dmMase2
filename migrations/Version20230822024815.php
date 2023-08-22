<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230822024815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE portrait (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, instance_id INTEGER DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenoms VARCHAR(255) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, biographie CLOB DEFAULT NULL, media VARCHAR(255) DEFAULT NULL, ordre INTEGER DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_954034FB3A51721D FOREIGN KEY (instance_id) REFERENCES instance (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_954034FB3A51721D ON portrait (instance_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE portrait');
    }
}
