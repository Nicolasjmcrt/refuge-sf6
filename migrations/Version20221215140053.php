<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221215140053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE races DROP FOREIGN KEY FK_5DBD1EC9727ACA70');
        $this->addSql('ALTER TABLE races ADD CONSTRAINT FK_5DBD1EC9727ACA70 FOREIGN KEY (parent_id) REFERENCES races (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE races DROP FOREIGN KEY FK_5DBD1EC9727ACA70');
        $this->addSql('ALTER TABLE races ADD CONSTRAINT FK_5DBD1EC9727ACA70 FOREIGN KEY (parent_id) REFERENCES races (id)');
    }
}
