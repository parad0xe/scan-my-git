<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210719133100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrator (id INT AUTO_INCREMENT NOT NULL, admin_id INT NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_58DF0651642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analysis (id INT AUTO_INCREMENT NOT NULL, context_id INT NOT NULL, score INT NOT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', finished_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_33C7306B00C1CF (context_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE context (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, github_url LONGTEXT NOT NULL, is_private TINYINT(1) NOT NULL, secret_id VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E25D857E7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE context_module (id INT AUTO_INCREMENT NOT NULL, module_id INT NOT NULL, context_id INT NOT NULL, command LONGTEXT NOT NULL, parameters LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_BBC86D7FAFC2B591 (module_id), INDEX IDX_BBC86D7F6B00C1CF (context_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_C24262812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE runner (id INT AUTO_INCREMENT NOT NULL, analysis_id INT NOT NULL, context_module_id INT NOT NULL, output LONGTEXT NOT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', finished_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F92B8B3E7941003F (analysis_id), INDEX IDX_F92B8B3E7C00B58E (context_module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, client_id VARCHAR(255) NOT NULL, is_deleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE administrator ADD CONSTRAINT FK_58DF0651642B8210 FOREIGN KEY (admin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE analysis ADD CONSTRAINT FK_33C7306B00C1CF FOREIGN KEY (context_id) REFERENCES context (id)');
        $this->addSql('ALTER TABLE context ADD CONSTRAINT FK_E25D857E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE context_module ADD CONSTRAINT FK_BBC86D7FAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE context_module ADD CONSTRAINT FK_BBC86D7F6B00C1CF FOREIGN KEY (context_id) REFERENCES context (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C24262812469DE2 FOREIGN KEY (category_id) REFERENCES module_category (id)');
        $this->addSql('ALTER TABLE runner ADD CONSTRAINT FK_F92B8B3E7941003F FOREIGN KEY (analysis_id) REFERENCES analysis (id)');
        $this->addSql('ALTER TABLE runner ADD CONSTRAINT FK_F92B8B3E7C00B58E FOREIGN KEY (context_module_id) REFERENCES context_module (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE runner DROP FOREIGN KEY FK_F92B8B3E7941003F');
        $this->addSql('ALTER TABLE analysis DROP FOREIGN KEY FK_33C7306B00C1CF');
        $this->addSql('ALTER TABLE context_module DROP FOREIGN KEY FK_BBC86D7F6B00C1CF');
        $this->addSql('ALTER TABLE runner DROP FOREIGN KEY FK_F92B8B3E7C00B58E');
        $this->addSql('ALTER TABLE context_module DROP FOREIGN KEY FK_BBC86D7FAFC2B591');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C24262812469DE2');
        $this->addSql('ALTER TABLE administrator DROP FOREIGN KEY FK_58DF0651642B8210');
        $this->addSql('ALTER TABLE context DROP FOREIGN KEY FK_E25D857E7E3C61F9');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE analysis');
        $this->addSql('DROP TABLE context');
        $this->addSql('DROP TABLE context_module');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE module_category');
        $this->addSql('DROP TABLE runner');
        $this->addSql('DROP TABLE `user`');
    }
}
