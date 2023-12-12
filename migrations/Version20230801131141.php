<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801131141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660B092A811');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660F603EE73');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, company_name VARCHAR(255) NOT NULL, company_description VARCHAR(255) NOT NULL, industry VARCHAR(255) NOT NULL, founded_year DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE store');
        $this->addSql('DROP TABLE vendor');
        $this->addSql('DROP INDEX UNIQ_4B365660B092A811 ON stock');
        $this->addSql('DROP INDEX UNIQ_4B365660F603EE73 ON stock');
        $this->addSql('ALTER TABLE stock ADD company_id INT DEFAULT NULL, ADD market_cap NUMERIC(10, 0) NOT NULL, DROP vendor_id, DROP store_id, DROP purchase_date, CHANGE stock_name symbol VARCHAR(255) NOT NULL, CHANGE price current_price NUMERIC(10, 0) NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B365660979B1AD6 ON stock (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660979B1AD6');
        $this->addSql('CREATE TABLE store (id INT AUTO_INCREMENT NOT NULL, store_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, store_location VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE vendor (id INT AUTO_INCREMENT NOT NULL, owner_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, owner_location VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP INDEX UNIQ_4B365660979B1AD6 ON stock');
        $this->addSql('ALTER TABLE stock ADD vendor_id INT NOT NULL, ADD store_id INT NOT NULL, ADD purchase_date DATETIME NOT NULL, ADD price NUMERIC(10, 0) NOT NULL, DROP company_id, DROP current_price, DROP market_cap, CHANGE symbol stock_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660B092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660F603EE73 FOREIGN KEY (vendor_id) REFERENCES vendor (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B365660B092A811 ON stock (store_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B365660F603EE73 ON stock (vendor_id)');
    }
}
