<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250904092521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genrequest (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, dcreated DATETIME NOT NULL, promtpositive LONGTEXT DEFAULT NULL, promtnegative LONGTEXT DEFAULT NULL, startprocessing DATETIME DEFAULT NULL, endprocessing DATETIME DEFAULT NULL, refused TINYINT(1) DEFAULT NULL, refusereason VARCHAR(255) DEFAULT NULL, priority INT NOT NULL, INDEX IDX_A5DF51FCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE genrequest ADD CONSTRAINT FK_A5DF51FCA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genrequest DROP FOREIGN KEY FK_A5DF51FCA76ED395');
        $this->addSql('DROP TABLE genrequest');
    }
}
