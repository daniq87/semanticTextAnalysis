<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170524102117 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE separator (id INTEGER NOT NULL, separator VARCHAR(255) NOT NULL, is_symbol BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP INDEX IDX_810763261F55203D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__criterias AS SELECT id, topic_id, name FROM criterias');
        $this->addSql('DROP TABLE criterias');
        $this->addSql('CREATE TABLE criterias (id INTEGER NOT NULL, topic_id INTEGER DEFAULT NULL, name VARCHAR(150) NOT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_810763261F55203D FOREIGN KEY (topic_id) REFERENCES topics (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO criterias (id, topic_id, name) SELECT id, topic_id, name FROM __temp__criterias');
        $this->addSql('DROP TABLE __temp__criterias');
        $this->addSql('CREATE INDEX IDX_810763261F55203D ON criterias (topic_id)');
        $this->addSql('DROP INDEX UNIQ_21171B4A3E2E969B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__review_score AS SELECT id, review_id, score, matches FROM review_score');
        $this->addSql('DROP TABLE review_score');
        $this->addSql('CREATE TABLE review_score (id INTEGER NOT NULL, review_id INTEGER DEFAULT NULL, score INTEGER NOT NULL, matches CLOB DEFAULT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_21171B4A3E2E969B FOREIGN KEY (review_id) REFERENCES reviews (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO review_score (id, review_id, score, matches) SELECT id, review_id, score, matches FROM __temp__review_score');
        $this->addSql('DROP TABLE __temp__review_score');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_21171B4A3E2E969B ON review_score (review_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE separator');
        $this->addSql('DROP INDEX IDX_810763261F55203D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__criterias AS SELECT id, topic_id, name FROM criterias');
        $this->addSql('DROP TABLE criterias');
        $this->addSql('CREATE TABLE criterias (id INTEGER NOT NULL, topic_id INTEGER DEFAULT NULL, name VARCHAR(150) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO criterias (id, topic_id, name) SELECT id, topic_id, name FROM __temp__criterias');
        $this->addSql('DROP TABLE __temp__criterias');
        $this->addSql('CREATE INDEX IDX_810763261F55203D ON criterias (topic_id)');
        $this->addSql('DROP INDEX UNIQ_21171B4A3E2E969B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__review_score AS SELECT id, review_id, score, matches FROM review_score');
        $this->addSql('DROP TABLE review_score');
        $this->addSql('CREATE TABLE review_score (id INTEGER NOT NULL, review_id INTEGER DEFAULT NULL, score INTEGER NOT NULL, matches CLOB DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO review_score (id, review_id, score, matches) SELECT id, review_id, score, matches FROM __temp__review_score');
        $this->addSql('DROP TABLE __temp__review_score');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_21171B4A3E2E969B ON review_score (review_id)');
    }
}
