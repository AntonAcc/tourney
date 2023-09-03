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
            (1, 'k1'),
            (2, 'k2'),
            (3, 'k3'),
            (4, 'k4'),
            (5, 'k5'),
            (6, 'k6')
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
