<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201219061835 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE depense');
        $this->addSql('CREATE TABLE depense (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, submit_at DATETIME NOT NULL, amount BIGINT NOT NULL, libelle VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_34059757A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_34059757A76ED395 ON depense (user_id)');
        $this->addSql('DROP TABLE revenu');
        $this->addSql('CREATE TABLE revenu (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, submit_at DATETIME NOT NULL, denomination VARCHAR(255) NOT NULL COLLATE BINARY, amount BIGINT NOT NULL, CONSTRAINT FK_7DA3C045A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7DA3C045A76ED395 ON revenu (user_id)');
        $this->addSql('ALTER TABLE user ADD COLUMN solde BIGINT NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_34059757A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__depense AS SELECT id, submit_at, amount, libelle FROM depense');
        $this->addSql('DROP TABLE depense');
        $this->addSql('CREATE TABLE depense (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, submit_at DATETIME NOT NULL, amount BIGINT NOT NULL, libelle VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO depense (id, submit_at, amount, libelle) SELECT id, submit_at, amount, libelle FROM __temp__depense');
        $this->addSql('DROP TABLE __temp__depense');
        $this->addSql('DROP INDEX IDX_7DA3C045A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__revenu AS SELECT id, submit_at, denomination, amount FROM revenu');
        $this->addSql('DROP TABLE revenu');
        $this->addSql('CREATE TABLE revenu (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, submit_at DATETIME NOT NULL, denomination VARCHAR(255) NOT NULL, amount BIGINT NOT NULL)');
        $this->addSql('INSERT INTO revenu (id, submit_at, denomination, amount) SELECT id, submit_at, denomination, amount FROM __temp__revenu');
        $this->addSql('DROP TABLE __temp__revenu');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, roles, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, username, roles, password) SELECT id, username, roles, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }
}
