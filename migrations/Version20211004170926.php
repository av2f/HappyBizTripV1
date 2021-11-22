<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211004170926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL,
        email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\',
        password VARCHAR(255) NOT NULL,
        first_name VARCHAR(100) NOT NULL,
        last_name VARCHAR(100) DEFAULT NULL,
        slug VARCHAR(255) NOT NULL,
        birth_date DATE NOT NULL,
        avatar VARCHAR(255) DEFAULT NULL,
        is_subscribed TINYINT(1) NOT NULL,
        is_active TINYINT(1) NOT NULL,
        is_deleted TINYINT(1) NOT NULL,
        completed SMALLINT NOT NULL,
        created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
        updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
        subscrib_pay_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
        subscrib_begin_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
        subscrib_end_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
        last_login DATETIME NOT NULL,
        UNIQUE INDEX UNIQ_8D93D649E7927C74 (email),
        PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
    }
}
