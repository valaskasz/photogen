<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250923103222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genfiles (id INT AUTO_INCREMENT NOT NULL, genrequest_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_946BCCE76F53EC71 (genrequest_id), INDEX IDX_946BCCE793CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE genfiles ADD CONSTRAINT FK_946BCCE76F53EC71 FOREIGN KEY (genrequest_id) REFERENCES genrequest (id)');
        $this->addSql('ALTER TABLE genfiles ADD CONSTRAINT FK_946BCCE793CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genfiles DROP FOREIGN KEY FK_946BCCE76F53EC71');
        $this->addSql('ALTER TABLE genfiles DROP FOREIGN KEY FK_946BCCE793CB796C');
        $this->addSql('DROP TABLE genfiles');
    }
}
