<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211220130115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE near_miss ADD year_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE near_miss ADD CONSTRAINT FK_789F860440C1FEA7 FOREIGN KEY (year_id) REFERENCES year (id)');
        $this->addSql('CREATE INDEX IDX_789F860440C1FEA7 ON near_miss (year_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE near_miss DROP FOREIGN KEY FK_789F860440C1FEA7');
        $this->addSql('DROP INDEX IDX_789F860440C1FEA7 ON near_miss');
        $this->addSql('ALTER TABLE near_miss DROP year_id');
    }
}
