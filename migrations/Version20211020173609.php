<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211020173609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription_type (id INT AUTO_INCREMENT NOT NULL, subscrib_name VARCHAR(50) NOT NULL, duration SMALLINT NOT NULL, duration_type VARCHAR(1) NOT NULL, price NUMERIC(6, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD subscription_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B6596C08 FOREIGN KEY (subscription_type_id) REFERENCES subscription_type (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B6596C08 ON user (subscription_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B6596C08');
        $this->addSql('DROP TABLE subscription_type');
        $this->addSql('DROP INDEX IDX_8D93D649B6596C08 ON user');
        $this->addSql('ALTER TABLE user DROP subscription_type_id');
    }
}
