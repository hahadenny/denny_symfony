<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220217021001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE User (Id INT AUTO_INCREMENT NOT NULL, UserName VARCHAR(50) NOT NULL, Token VARCHAR(255) NOT NULL, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Vehicle (Id INT AUTO_INCREMENT NOT NULL, DateAdded DATETIME NOT NULL, Type VARCHAR(4) NOT NULL, Msrp NUMERIC(20, 2) DEFAULT NULL, Year INT(4) NOT NULL, Make VARCHAR(50) NOT NULL, Model VARCHAR(50) NOT NULL, Miles INT DEFAULT NULL, Vin VARCHAR(50) DEFAULT NULL, Deleted TINYINT(1) NOT NULL default 0, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    	$this->addSql("INSERT INTO User SET UserName='$_ENV[TEST_USERNAME]', Token='$_ENV[TEST_TOKEN]'");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE Vehicle');
    }
}
