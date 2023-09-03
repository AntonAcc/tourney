<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230903112436 extends AbstractMigration
{
    /**
     * {@inheritDoc}
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tourney.team_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tourney.team (
            id INT NOT NULL,
            name VARCHAR(255) NOT NULL, 
            PRIMARY KEY(id)
        )');
    }

    /**
     * {@inheritDoc}
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE team_id_seq CASCADE');
        $this->addSql('DROP TABLE tourney.team');
    }
}
