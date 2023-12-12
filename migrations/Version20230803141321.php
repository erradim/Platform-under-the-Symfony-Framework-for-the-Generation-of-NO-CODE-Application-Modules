<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230803141321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pak (id INT AUTO_INCREMENT NOT NULL, zasx VARCHAR(255) NOT NULL, wxaz DATETIME NOT NULL, revec LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, pak_id INT DEFAULT NULL, aze SMALLINT NOT NULL, ezar BIGINT NOT NULL, revectrbgc LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', uny TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_4B3656606B959EBF (pak_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656606B959EBF FOREIGN KEY (pak_id) REFERENCES pak (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656606B959EBF');
        $this->addSql('DROP TABLE pak');
        $this->addSql('DROP TABLE stock');
    }
}
