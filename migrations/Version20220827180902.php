<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220827180902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, deleted TINYINT(1) NOT NULL, service TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tool (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(15) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tool_status (id INT AUTO_INCREMENT NOT NULL, tool_id_id INT DEFAULT NULL, status_id_id INT DEFAULT NULL, INDEX IDX_1E5169009E91440F (tool_id_id), INDEX IDX_1E516900881ECFA7 (status_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tool_status ADD CONSTRAINT FK_1E5169009E91440F FOREIGN KEY (tool_id_id) REFERENCES tool (id)');
        $this->addSql('ALTER TABLE tool_status ADD CONSTRAINT FK_1E516900881ECFA7 FOREIGN KEY (status_id_id) REFERENCES status (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tool_status DROP FOREIGN KEY FK_1E5169009E91440F');
        $this->addSql('ALTER TABLE tool_status DROP FOREIGN KEY FK_1E516900881ECFA7');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE tool');
        $this->addSql('DROP TABLE tool_status');
    }
}
