<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930090059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fileuser (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, file_id INT NOT NULL, dcreated DATETIME NOT NULL, INDEX IDX_EFBB412AA76ED395 (user_id), INDEX IDX_EFBB412A93CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fileuser ADD CONSTRAINT FK_EFBB412AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE fileuser ADD CONSTRAINT FK_EFBB412A93CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fileuser DROP FOREIGN KEY FK_EFBB412AA76ED395');
        $this->addSql('ALTER TABLE fileuser DROP FOREIGN KEY FK_EFBB412A93CB796C');
        $this->addSql('DROP TABLE fileuser');
    }
}
