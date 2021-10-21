<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021063238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription_history (id INT AUTO_INCREMENT NOT NULL, subscriber_id INT DEFAULT NULL, subscription_type_id INT DEFAULT NULL, subscrib_pay_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', subscrib_begin_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', subscrib_end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_54AF90D07808B1AD (subscriber_id), INDEX IDX_54AF90D0B6596C08 (subscription_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscription_history ADD CONSTRAINT FK_54AF90D07808B1AD FOREIGN KEY (subscriber_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription_history ADD CONSTRAINT FK_54AF90D0B6596C08 FOREIGN KEY (subscription_type_id) REFERENCES subscription_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE subscription_history');
    }
}
