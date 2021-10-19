<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211010095955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD gender VARCHAR(1) DEFAULT NULL, ADD first_name VARCHAR(100) NOT NULL, ADD last_name VARCHAR(100) DEFAULT NULL, ADD slug VARCHAR(255) NOT NULL, ADD birth_date DATE NOT NULL, ADD situation VARCHAR(1) DEFAULT NULL, ADD avatar VARCHAR(255) DEFAULT NULL, ADD profession VARCHAR(100) DEFAULT NULL, ADD company VARCHAR(100) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL, ADD is_subscribed TINYINT(1) NOT NULL, ADD is_active TINYINT(1) NOT NULL, ADD is_deleted TINYINT(1) NOT NULL, ADD completed SMALLINT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD subscrib_pay_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD subscrib_begin_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD subscrib_end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP gender, DROP first_name, DROP last_name, DROP slug, DROP birth_date, DROP situation, DROP avatar, DROP profession, DROP company, DROP description, DROP is_subscribed, DROP is_active, DROP is_deleted, DROP completed, DROP created_at, DROP updated_at, DROP subscrib_pay_at, DROP subscrib_begin_at, DROP subscrib_end_at');
    }
}
