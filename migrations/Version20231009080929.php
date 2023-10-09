<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231009080929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, entreprise_id INT DEFAULT NULL, commentaire VARCHAR(1000) DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_8F91ABF0A76ED395 (user_id), INDEX IDX_8F91ABF0A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cotation (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, entreprise_id INT NOT NULL, note INT NOT NULL, INDEX IDX_996DA944A76ED395 (user_id), INDEX IDX_996DA944A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE critere (id INT AUTO_INCREMENT NOT NULL, questioncritere VARCHAR(800) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE critere_cotation (critere_id INT NOT NULL, cotation_id INT NOT NULL, INDEX IDX_7AFBB8F29E5F45AB (critere_id), INDEX IDX_7AFBB8F25D14FAF0 (cotation_id), PRIMARY KEY(critere_id, cotation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, secteur VARCHAR(255) NOT NULL, emplacement VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, description VARCHAR(800) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE cotation ADD CONSTRAINT FK_996DA944A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE cotation ADD CONSTRAINT FK_996DA944A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE critere_cotation ADD CONSTRAINT FK_7AFBB8F29E5F45AB FOREIGN KEY (critere_id) REFERENCES critere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE critere_cotation ADD CONSTRAINT FK_7AFBB8F25D14FAF0 FOREIGN KEY (cotation_id) REFERENCES cotation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0A76ED395');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0A4AEAFEA');
        $this->addSql('ALTER TABLE cotation DROP FOREIGN KEY FK_996DA944A76ED395');
        $this->addSql('ALTER TABLE cotation DROP FOREIGN KEY FK_996DA944A4AEAFEA');
        $this->addSql('ALTER TABLE critere_cotation DROP FOREIGN KEY FK_7AFBB8F29E5F45AB');
        $this->addSql('ALTER TABLE critere_cotation DROP FOREIGN KEY FK_7AFBB8F25D14FAF0');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE cotation');
        $this->addSql('DROP TABLE critere');
        $this->addSql('DROP TABLE critere_cotation');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
