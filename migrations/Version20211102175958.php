<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211102175958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, identifier VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_880E0D76A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_professional (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_professional_professional (id INT AUTO_INCREMENT NOT NULL, professional_id INT DEFAULT NULL, category_professional_id INT DEFAULT NULL, INDEX IDX_ADA5750EDB77003 (professional_id), INDEX IDX_ADA5750E553E2C51 (category_professional_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2D5B023498260155 (region_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, iso_code VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, continent_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE faq (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, answer LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, iso_code VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, legend VARCHAR(255) DEFAULT NULL, type INT NOT NULL, INDEX IDX_6A2CA10CED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professional (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, region_id INT DEFAULT NULL, city_id INT DEFAULT NULL, user_id INT DEFAULT NULL, category_professional_default_id INT DEFAULT NULL, short_description LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, status TINYINT(1) NOT NULL, verified TINYINT(1) NOT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, INDEX IDX_B3B573AAF92F3E70 (country_id), INDEX IDX_B3B573AA98260155 (region_id), INDEX IDX_B3B573AA8BAC62AF (city_id), UNIQUE INDEX UNIQ_B3B573AAA76ED395 (user_id), UNIQUE INDEX UNIQ_B3B573AA998005F6 (category_professional_default_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professional_language (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, professional_id INT DEFAULT NULL, INDEX IDX_475DAB0882F1BAF4 (language_id), INDEX IDX_475DAB08DB77003 (professional_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professional_service (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, professional_id INT DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, unit VARCHAR(255) DEFAULT NULL, INDEX IDX_6EB8BA5FED5CA9E6 (service_id), INDEX IDX_6EB8BA5FDB77003 (professional_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE professional_social_media (id INT AUTO_INCREMENT NOT NULL, social_media_id INT DEFAULT NULL, professional_id INT DEFAULT NULL, INDEX IDX_4B65CE0C64AE4959 (social_media_id), INDEX IDX_4B65CE0CDB77003 (professional_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE qualification (id INT AUTO_INCREMENT NOT NULL, professional_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, place VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, status TINYINT(1) NOT NULL, type INT NOT NULL, INDEX IDX_B712F0CEDB77003 (professional_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_F62F176F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_media (id INT AUTO_INCREMENT NOT NULL, icon VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, date_add DATETIME NOT NULL, date_upd DATETIME NOT NULL, status TINYINT(1) NOT NULL, phone VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE category_professional_professional ADD CONSTRAINT FK_ADA5750EDB77003 FOREIGN KEY (professional_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE category_professional_professional ADD CONSTRAINT FK_ADA5750E553E2C51 FOREIGN KEY (category_professional_id) REFERENCES category_professional (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B023498260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE professional ADD CONSTRAINT FK_B3B573AAF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE professional ADD CONSTRAINT FK_B3B573AA98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE professional ADD CONSTRAINT FK_B3B573AA8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE professional ADD CONSTRAINT FK_B3B573AAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE professional ADD CONSTRAINT FK_B3B573AA998005F6 FOREIGN KEY (category_professional_default_id) REFERENCES category_professional (id)');
        $this->addSql('ALTER TABLE professional_language ADD CONSTRAINT FK_475DAB0882F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE professional_language ADD CONSTRAINT FK_475DAB08DB77003 FOREIGN KEY (professional_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE professional_service ADD CONSTRAINT FK_6EB8BA5FED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE professional_service ADD CONSTRAINT FK_6EB8BA5FDB77003 FOREIGN KEY (professional_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE professional_social_media ADD CONSTRAINT FK_4B65CE0C64AE4959 FOREIGN KEY (social_media_id) REFERENCES social_media (id)');
        $this->addSql('ALTER TABLE professional_social_media ADD CONSTRAINT FK_4B65CE0CDB77003 FOREIGN KEY (professional_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE qualification ADD CONSTRAINT FK_B712F0CEDB77003 FOREIGN KEY (professional_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F176F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_professional_professional DROP FOREIGN KEY FK_ADA5750E553E2C51');
        $this->addSql('ALTER TABLE professional DROP FOREIGN KEY FK_B3B573AA998005F6');
        $this->addSql('ALTER TABLE professional DROP FOREIGN KEY FK_B3B573AA8BAC62AF');
        $this->addSql('ALTER TABLE professional DROP FOREIGN KEY FK_B3B573AAF92F3E70');
        $this->addSql('ALTER TABLE region DROP FOREIGN KEY FK_F62F176F92F3E70');
        $this->addSql('ALTER TABLE professional_language DROP FOREIGN KEY FK_475DAB0882F1BAF4');
        $this->addSql('ALTER TABLE category_professional_professional DROP FOREIGN KEY FK_ADA5750EDB77003');
        $this->addSql('ALTER TABLE professional_language DROP FOREIGN KEY FK_475DAB08DB77003');
        $this->addSql('ALTER TABLE professional_service DROP FOREIGN KEY FK_6EB8BA5FDB77003');
        $this->addSql('ALTER TABLE professional_social_media DROP FOREIGN KEY FK_4B65CE0CDB77003');
        $this->addSql('ALTER TABLE qualification DROP FOREIGN KEY FK_B712F0CEDB77003');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B023498260155');
        $this->addSql('ALTER TABLE professional DROP FOREIGN KEY FK_B3B573AA98260155');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CED5CA9E6');
        $this->addSql('ALTER TABLE professional_service DROP FOREIGN KEY FK_6EB8BA5FED5CA9E6');
        $this->addSql('ALTER TABLE professional_social_media DROP FOREIGN KEY FK_4B65CE0C64AE4959');
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76A76ED395');
        $this->addSql('ALTER TABLE professional DROP FOREIGN KEY FK_B3B573AAA76ED395');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE category_professional');
        $this->addSql('DROP TABLE category_professional_professional');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE faq');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE professional');
        $this->addSql('DROP TABLE professional_language');
        $this->addSql('DROP TABLE professional_service');
        $this->addSql('DROP TABLE professional_social_media');
        $this->addSql('DROP TABLE qualification');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE social_media');
        $this->addSql('DROP TABLE user');
    }
}
