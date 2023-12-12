<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230810111736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, azer_id INT DEFAULT NULL, stranger_id INT DEFAULT NULL, a SMALLINT NOT NULL, azesqdf VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4B36566013F03A80 (azer_id), UNIQUE INDEX UNIQ_4B365660F66D6FD8 (stranger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stranger (id INT AUTO_INCREMENT NOT NULL, azesqdfz_sx LONGBLOB NOT NULL, azesqdfz_sxxwcv DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B36566013F03A80 FOREIGN KEY (azer_id) REFERENCES azer (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660F66D6FD8 FOREIGN KEY (stranger_id) REFERENCES stranger (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B36566013F03A80');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660F66D6FD8');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE stranger');
    }
}
