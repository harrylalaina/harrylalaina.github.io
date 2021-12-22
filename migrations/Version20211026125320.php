<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211026125320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, type_status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE near_miss ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE near_miss ADD CONSTRAINT FK_789F86046BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('CREATE INDEX IDX_789F86046BF700BD ON near_miss (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE near_miss DROP FOREIGN KEY FK_789F86046BF700BD');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP INDEX IDX_789F86046BF700BD ON near_miss');
        $this->addSql('ALTER TABLE near_miss DROP status_id');
    }
}
