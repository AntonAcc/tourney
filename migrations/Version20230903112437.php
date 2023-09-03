<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230903112437 extends AbstractMigration
{
    /**
     * {@inheritDoc}
     */
    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO team VALUES
            (nextval('tourney.team_id_seq'),'k1'),
            (nextval('tourney.team_id_seq'),'k2'),
            (nextval('tourney.team_id_seq'),'k3'),
            (nextval('tourney.team_id_seq'),'k4'),
            (nextval('tourney.team_id_seq'),'k5'),
            (nextval('tourney.team_id_seq'),'k6')
        ");
    }

    /**
     * {@inheritDoc}
     */
    public function down(Schema $schema): void
    {
        $this->addSql('TRUNCATE team');
    }
}
