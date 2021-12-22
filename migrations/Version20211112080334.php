<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211112080334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE near_miss ADD categorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE near_miss ADD CONSTRAINT FK_789F8604BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_789F8604BCF5E72D ON near_miss (categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE near_miss DROP FOREIGN KEY FK_789F8604BCF5E72D');
        $this->addSql('DROP INDEX IDX_789F8604BCF5E72D ON near_miss');
        $this->addSql('ALTER TABLE near_miss DROP categorie_id');
    }
}
