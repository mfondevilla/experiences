<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260823090120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE experiences (id VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, provider_id VARCHAR(255) NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE reservations (id VARCHAR(255) NOT NULL, session_id VARCHAR(255) NOT NULL, user_id VARCHAR(255) NOT NULL, seats INTEGER NOT NULL, total_price DOUBLE PRECISION NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE TABLE sessions (id VARCHAR(255) NOT NULL, experience_id VARCHAR(255) NOT NULL, start_at DATETIME NOT NULL, capacity INTEGER NOT NULL, available_seats INTEGER NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY (id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE experiences');
        $this->addSql('DROP TABLE reservations');
        $this->addSql('DROP TABLE sessions');
    }
}
