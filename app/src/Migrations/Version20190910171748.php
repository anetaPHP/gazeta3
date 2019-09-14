<?php

declare(strict_types=1);

/**
 * Summary Migration2.
 */

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190910171748 extends AbstractMigration
{
    /**
     * GetDescription.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * UP Schema.
     *
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE article CHANGE category_id category_id INT DEFAULT NULL, 
        CHANGE subtitle subtitle VARCHAR(180) DEFAULT NULL');
    }

    /**
     * Down Schema.
     *
     * @param Schema $schema
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            'mysql' !== $this->connection->getDatabasePlatform()->getName(),
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE article CHANGE category_id category_id INT DEFAULT NULL, 
        CHANGE subtitle subtitle VARCHAR(180) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
