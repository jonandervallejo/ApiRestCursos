<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241010191847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE asignaturas (id INT AUTO_INCREMENT NOT NULL, id_curso_id INT NOT NULL, curso_id INT DEFAULT NULL, nombre VARCHAR(255) DEFAULT NULL, horas VARCHAR(255) DEFAULT NULL, INDEX IDX_6740636AD710A68A (id_curso_id), INDEX IDX_6740636A87CB4A1F (curso_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE curso (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE asignaturas ADD CONSTRAINT FK_6740636AD710A68A FOREIGN KEY (id_curso_id) REFERENCES curso (id)');
        $this->addSql('ALTER TABLE asignaturas ADD CONSTRAINT FK_6740636A87CB4A1F FOREIGN KEY (curso_id) REFERENCES curso (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asignaturas DROP FOREIGN KEY FK_6740636AD710A68A');
        $this->addSql('ALTER TABLE asignaturas DROP FOREIGN KEY FK_6740636A87CB4A1F');
        $this->addSql('DROP TABLE asignaturas');
        $this->addSql('DROP TABLE curso');
    }
}
