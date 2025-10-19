<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250919150432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genrequest ADD processingunit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE genrequest ADD CONSTRAINT FK_A5DF51FC32F3F8DE FOREIGN KEY (processingunit_id) REFERENCES processingunit (id)');
        $this->addSql('CREATE INDEX IDX_A5DF51FC32F3F8DE ON genrequest (processingunit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genrequest DROP FOREIGN KEY FK_A5DF51FC32F3F8DE');
        $this->addSql('DROP INDEX IDX_A5DF51FC32F3F8DE ON genrequest');
        $this->addSql('ALTER TABLE genrequest DROP processingunit_id');
    }
}
