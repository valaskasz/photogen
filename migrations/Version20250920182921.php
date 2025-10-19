<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250920182921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, processingunit_id INT NOT NULL, filename VARCHAR(255) NOT NULL, directory VARCHAR(255) NOT NULL, INDEX IDX_8C9F361032F3F8DE (processingunit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filerequest (id INT AUTO_INCREMENT NOT NULL, file_id INT NOT NULL, user_id INT NOT NULL, tostore TINYINT(1) NOT NULL, finished TINYINT(1) NOT NULL, dcreated DATETIME NOT NULL, dfinished DATETIME DEFAULT NULL, INDEX IDX_7AB34EA393CB796C (file_id), INDEX IDX_7AB34EA3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361032F3F8DE FOREIGN KEY (processingunit_id) REFERENCES processingunit (id)');
        $this->addSql('ALTER TABLE filerequest ADD CONSTRAINT FK_7AB34EA393CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE filerequest ADD CONSTRAINT FK_7AB34EA3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361032F3F8DE');
        $this->addSql('ALTER TABLE filerequest DROP FOREIGN KEY FK_7AB34EA393CB796C');
        $this->addSql('ALTER TABLE filerequest DROP FOREIGN KEY FK_7AB34EA3A76ED395');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE filerequest');
    }
}
