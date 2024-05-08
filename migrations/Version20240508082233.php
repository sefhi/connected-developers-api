<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508082233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Created register connect table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app_register_connect (id UUID NOT NULL, username1 VARCHAR(255) NOT NULL, username2 VARCHAR(255) NOT NULL, connected BOOLEAN NOT NULL, organizations TEXT NOT NULL, registered_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN app_register_connect.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN app_register_connect.organizations IS \'(DC2Type:array)\'');
        $this->addSql('COMMENT ON COLUMN app_register_connect.registered_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE app_register_connect');
    }
}
