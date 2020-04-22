<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200422142241 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE place_has_service (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, place_id INT NOT NULL, price NUMERIC(13, 2) NOT NULL, INDEX IDX_8899BAADED5CA9E6 (service_id), INDEX IDX_8899BAADDA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_feedback_place (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, place_id INT NOT NULL, rate SMALLINT NOT NULL, feedback VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_8B3915F1A76ED395 (user_id), INDEX IDX_8B3915F1DA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_saved_place (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, place_id INT NOT NULL, date DATE NOT NULL, INDEX IDX_B0D54141A76ED395 (user_id), INDEX IDX_B0D54141DA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE place_has_service ADD CONSTRAINT FK_8899BAADED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE place_has_service ADD CONSTRAINT FK_8899BAADDA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE user_feedback_place ADD CONSTRAINT FK_8B3915F1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_feedback_place ADD CONSTRAINT FK_8B3915F1DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('ALTER TABLE user_saved_place ADD CONSTRAINT FK_B0D54141A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_saved_place ADD CONSTRAINT FK_B0D54141DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE place_has_service');
        $this->addSql('DROP TABLE user_feedback_place');
        $this->addSql('DROP TABLE user_saved_place');
    }
}
