<?php
/**
 * @author Anton Acc <me@anton-a.cc>
 */
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904170950 extends AbstractMigration
{
    /**
     * {@inheritDoc}
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tournament_game_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tournament_game (id INT NOT NULL, tournament_id INT NOT NULL, team_one_id INT NOT NULL, team_two_id INT NOT NULL, day INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_14A683B233D1A3E7 ON tournament_game (tournament_id)');
        $this->addSql('CREATE INDEX IDX_14A683B28D8189CA ON tournament_game (team_one_id)');
        $this->addSql('CREATE INDEX IDX_14A683B2E6DD6E05 ON tournament_game (team_two_id)');
        $this->addSql('ALTER TABLE tournament_game ADD CONSTRAINT FK_14A683B233D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_game ADD CONSTRAINT FK_14A683B28D8189CA FOREIGN KEY (team_one_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_game ADD CONSTRAINT FK_14A683B2E6DD6E05 FOREIGN KEY (team_two_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * {@inheritDoc}
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE tournament_game_id_seq CASCADE');
        $this->addSql('ALTER TABLE tournament_game DROP CONSTRAINT FK_14A683B233D1A3E7');
        $this->addSql('ALTER TABLE tournament_game DROP CONSTRAINT FK_14A683B28D8189CA');
        $this->addSql('ALTER TABLE tournament_game DROP CONSTRAINT FK_14A683B2E6DD6E05');
        $this->addSql('DROP TABLE tournament_game');
    }
}
