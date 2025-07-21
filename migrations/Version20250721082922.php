<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721082922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE station (id SERIAL NOT NULL, station_id VARCHAR(64) NOT NULL, name VARCHAR(255) NOT NULL, wmo_id VARCHAR(64) DEFAULT NULL, begin_date VARCHAR(255) DEFAULT NULL, end_date VARCHAR(255) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, elevation DOUBLE PRECISION DEFAULT NULL, gauss1 DOUBLE PRECISION DEFAULT NULL, gauss2 DOUBLE PRECISION DEFAULT NULL, geogr1 DOUBLE PRECISION DEFAULT NULL, geogr2 DOUBLE PRECISION DEFAULT NULL, elevation_pressure DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE station');
    }
}
