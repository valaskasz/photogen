<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250930092008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genrequest ADD inputfile_id INT DEFAULT NULL, ADD useinputfile TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE genrequest ADD CONSTRAINT FK_A5DF51FCA64C17B2 FOREIGN KEY (inputfile_id) REFERENCES file (id)');
        $this->addSql('CREATE INDEX IDX_A5DF51FCA64C17B2 ON genrequest (inputfile_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genrequest DROP FOREIGN KEY FK_A5DF51FCA64C17B2');
        $this->addSql('DROP INDEX IDX_A5DF51FCA64C17B2 ON genrequest');
        $this->addSql('ALTER TABLE genrequest DROP inputfile_id, DROP useinputfile');
    }
}
