<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231103082231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, title VARCHAR(255) NOT NULL, type VARCHAR(16) NOT NULL, path VARCHAR(255) NOT NULL, alt VARCHAR(255) NOT NULL, INDEX IDX_6A2CA10CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_content (media_id INT NOT NULL, content_id INT NOT NULL, INDEX IDX_9F12B6E0EA9FDD75 (media_id), INDEX IDX_9F12B6E084A0A3ED (content_id), PRIMARY KEY(media_id, content_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE media_content ADD CONSTRAINT FK_9F12B6E0EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_content ADD CONSTRAINT FK_9F12B6E084A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CA76ED395');
        $this->addSql('ALTER TABLE media_content DROP FOREIGN KEY FK_9F12B6E0EA9FDD75');
        $this->addSql('ALTER TABLE media_content DROP FOREIGN KEY FK_9F12B6E084A0A3ED');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_content');
    }
}
