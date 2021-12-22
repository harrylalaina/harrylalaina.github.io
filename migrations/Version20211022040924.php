<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211022040924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE near_miss ADD action_possible LONGTEXT DEFAULT NULL, ADD responsable VARCHAR(255) DEFAULT NULL, ADD action_possible01 LONGTEXT DEFAULT NULL, ADD responsable01 VARCHAR(255) DEFAULT NULL, ADD action_possible02 LONGTEXT DEFAULT NULL, ADD responsable02 VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE near_miss DROP action_possible, DROP responsable, DROP action_possible01, DROP responsable01, DROP action_possible02, DROP responsable02');
    }
}
