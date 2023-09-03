<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230903112435 extends AbstractMigration
{
    /**
     * {@inheritDoc}
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('CREATE SCHEMA IF NOT EXISTS tourney');
    }

    /**
     * {@inheritDoc}
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SCHEMA tourney CASCADE');
    }
}
