<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211227053721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, definition LONGTEXT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, name VARCHAR(255) NOT NULL, nom_responsable VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_F804D3B95E237E06 (name), INDEX IDX_F804D3B9ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mesure (id INT AUTO_INCREMENT NOT NULL, action_possible LONGTEXT DEFAULT NULL, responsable VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE near_miss (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, status_id INT NOT NULL, categorie_id INT NOT NULL, niveau_id INT DEFAULT NULL, year_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, action_immediate LONGTEXT NOT NULL, niveau_risque VARCHAR(255) NOT NULL, tache_affecte TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', action_possible LONGTEXT DEFAULT NULL, responsable VARCHAR(255) DEFAULT NULL, action_possible01 LONGTEXT DEFAULT NULL, responsable01 VARCHAR(255) DEFAULT NULL, action_possible02 LONGTEXT DEFAULT NULL, responsable02 VARCHAR(255) DEFAULT NULL, support VARCHAR(255) DEFAULT NULL, action_prevention LONGTEXT DEFAULT NULL, week INT DEFAULT NULL, preuve VARCHAR(255) DEFAULT NULL, closed_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_789F86041B65292 (employe_id), INDEX IDX_789F86046BF700BD (status_id), INDEX IDX_789F8604BCF5E72D (categorie_id), INDEX IDX_789F8604B3E9C81 (niveau_id), INDEX IDX_789F860440C1FEA7 (year_id), FULLTEXT INDEX IDX_789F8604FF7747B46DE44026 (titre, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, type_niveau VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, nom_service VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E19D9AD21412911A (nom_service), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, type_status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE traitement (id INT AUTO_INCREMENT NOT NULL, nearmiss_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_2A356D2760E43A14 (nearmiss_id), INDEX IDX_2A356D27A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, profil VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE year (id INT AUTO_INCREMENT NOT NULL, debut DATETIME NOT NULL, fin DATETIME NOT NULL, libelle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE near_miss ADD CONSTRAINT FK_789F86041B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE near_miss ADD CONSTRAINT FK_789F86046BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE near_miss ADD CONSTRAINT FK_789F8604BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE near_miss ADD CONSTRAINT FK_789F8604B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE near_miss ADD CONSTRAINT FK_789F860440C1FEA7 FOREIGN KEY (year_id) REFERENCES year (id)');
        $this->addSql('ALTER TABLE traitement ADD CONSTRAINT FK_2A356D2760E43A14 FOREIGN KEY (nearmiss_id) REFERENCES near_miss (id)');
        $this->addSql('ALTER TABLE traitement ADD CONSTRAINT FK_2A356D27A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE near_miss DROP FOREIGN KEY FK_789F8604BCF5E72D');
        $this->addSql('ALTER TABLE near_miss DROP FOREIGN KEY FK_789F86041B65292');
        $this->addSql('ALTER TABLE traitement DROP FOREIGN KEY FK_2A356D2760E43A14');
        $this->addSql('ALTER TABLE near_miss DROP FOREIGN KEY FK_789F8604B3E9C81');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9ED5CA9E6');
        $this->addSql('ALTER TABLE near_miss DROP FOREIGN KEY FK_789F86046BF700BD');
        $this->addSql('ALTER TABLE traitement DROP FOREIGN KEY FK_2A356D27A76ED395');
        $this->addSql('ALTER TABLE near_miss DROP FOREIGN KEY FK_789F860440C1FEA7');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE mesure');
        $this->addSql('DROP TABLE near_miss');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE traitement');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE year');
    }
}
