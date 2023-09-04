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
final class Version20230904064224 extends AbstractMigration
{
    /**
     * {@inheritDoc}
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE tournament_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tournament (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE tournament_team (tournament_id INT NOT NULL, team_id INT NOT NULL, PRIMARY KEY(tournament_id, team_id))');
        $this->addSql('CREATE INDEX IDX_F36D142133D1A3E7 ON tournament_team (tournament_id)');
        $this->addSql('CREATE INDEX IDX_F36D1421296CD8AE ON tournament_team (team_id)');
        $this->addSql('ALTER TABLE tournament_team ADD CONSTRAINT FK_F36D142133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_team ADD CONSTRAINT FK_F36D1421296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * {@inheritDoc}
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE tournament_id_seq CASCADE');
        $this->addSql('ALTER TABLE tournament_team DROP CONSTRAINT FK_F36D142133D1A3E7');
        $this->addSql('ALTER TABLE tournament_team DROP CONSTRAINT FK_F36D1421296CD8AE');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_team');
    }
}
