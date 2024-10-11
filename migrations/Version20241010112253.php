<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010112253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asignaturas ADD id_curso_id INT NOT NULL');
        $this->addSql('ALTER TABLE asignaturas ADD CONSTRAINT FK_6740636AD710A68A FOREIGN KEY (id_curso_id) REFERENCES curso (id)');
        $this->addSql('CREATE INDEX IDX_6740636AD710A68A ON asignaturas (id_curso_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asignaturas DROP FOREIGN KEY FK_6740636AD710A68A');
        $this->addSql('DROP INDEX IDX_6740636AD710A68A ON asignaturas');
        $this->addSql('ALTER TABLE asignaturas DROP id_curso_id');
    }
}
