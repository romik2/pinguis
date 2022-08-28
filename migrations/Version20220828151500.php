<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220828151500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE status CHANGE service service TINYINT(1) DEFAULT NULL');
        $this->addSql('INSERT INTO `status` (`name`, `deleted`, `service`) VALUES ("Включен", "0", "1")');
        $this->addSql('INSERT INTO `status` (`name`, `deleted`, `service`) VALUES ("Выключено", "0", "0")');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE status CHANGE service service VARCHAR(255) DEFAULT NULL');
    }
}
