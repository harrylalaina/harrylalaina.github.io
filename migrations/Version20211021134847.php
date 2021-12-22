<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021134847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mesure DROP FOREIGN KEY FK_5F1B6E7060E43A14');
        $this->addSql('DROP INDEX IDX_5F1B6E7060E43A14 ON mesure');
        $this->addSql('ALTER TABLE mesure DROP nearmiss_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mesure ADD nearmiss_id INT NOT NULL');
        $this->addSql('ALTER TABLE mesure ADD CONSTRAINT FK_5F1B6E7060E43A14 FOREIGN KEY (nearmiss_id) REFERENCES near_miss (id)');
        $this->addSql('CREATE INDEX IDX_5F1B6E7060E43A14 ON mesure (nearmiss_id)');
    }
}
