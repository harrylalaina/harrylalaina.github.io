<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211124055853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE near_miss ADD niveau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE near_miss ADD CONSTRAINT FK_789F8604B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('CREATE INDEX IDX_789F8604B3E9C81 ON near_miss (niveau_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE near_miss DROP FOREIGN KEY FK_789F8604B3E9C81');
        $this->addSql('DROP INDEX IDX_789F8604B3E9C81 ON near_miss');
        $this->addSql('ALTER TABLE near_miss DROP niveau_id');
    }
}
