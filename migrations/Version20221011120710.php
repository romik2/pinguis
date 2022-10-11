<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221011120710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tool ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tool ADD CONSTRAINT FK_20F33ED1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_20F33ED1A76ED395 ON tool (user_id)');
        $this->addSql('ALTER TABLE user ADD telegram_chat_id INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tool DROP FOREIGN KEY FK_20F33ED1A76ED395');
        $this->addSql('DROP INDEX IDX_20F33ED1A76ED395 ON tool');
        $this->addSql('ALTER TABLE tool DROP user_id');
        $this->addSql('ALTER TABLE user DROP telegram_chat_id');
    }
}
