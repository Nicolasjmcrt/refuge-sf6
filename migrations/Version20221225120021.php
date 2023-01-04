<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221225120021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animals ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animals ADD CONSTRAINT FK_966C69DDA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_966C69DDA76ED395 ON animals (user_id)');
        $this->addSql('ALTER TABLE races CHANGE race_order race_order INT NOT NULL');
        $this->addSql('ALTER TABLE refuges CHANGE zipcode zipcode VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE reset_token reset_token VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animals DROP FOREIGN KEY FK_966C69DDA76ED395');
        $this->addSql('DROP INDEX IDX_966C69DDA76ED395 ON animals');
        $this->addSql('ALTER TABLE animals DROP user_id');
        $this->addSql('ALTER TABLE races CHANGE race_order race_order INT DEFAULT NULL');
        $this->addSql('ALTER TABLE refuges CHANGE zipcode zipcode VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE reset_token reset_token VARCHAR(100) DEFAULT NULL');
    }
}
