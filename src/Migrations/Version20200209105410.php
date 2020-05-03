<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200209105410 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, locale VARCHAR(5) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, template_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, text LONGTEXT NOT NULL, created DATETIME DEFAULT NULL, modified DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, url_photo VARCHAR(300) DEFAULT NULL, published DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, locale VARCHAR(5) DEFAULT NULL, INDEX IDX_5A8A6C8DA76ED395 (user_id), INDEX IDX_5A8A6C8D5DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_category (post_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_B9A190604B89032C (post_id), INDEX IDX_B9A1906012469DE2 (category_id), PRIMARY KEY(post_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, fullname VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, roles LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', url_avatar VARCHAR(300) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE folder (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, parent_id INT DEFAULT NULL, created DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, active TINYINT(1) DEFAULT \'1\' NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_post_rel (id INT AUTO_INCREMENT NOT NULL, post_id INT DEFAULT NULL, media_id INT DEFAULT NULL, created DATETIME DEFAULT NULL, position INT DEFAULT NULL, INDEX IDX_A29C747CEA9FDD75 (media_id), INDEX IDX_A29C747C4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created DATETIME DEFAULT NULL, modified DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, view VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, folder_id INT DEFAULT NULL, type_id INT NOT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created DATETIME DEFAULT NULL, deleted DATETIME DEFAULT NULL, file VARCHAR(255) NOT NULL, external TINYINT(1) DEFAULT \'0\', INDEX IDX_6A2CA10C162CB942 (folder_id), INDEX IDX_6A2CA10CC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, google_gtag_id VARCHAR(255) DEFAULT NULL, url_facebook VARCHAR(255) DEFAULT NULL, url_instagram VARCHAR(255) DEFAULT NULL, url_linkedin VARCHAR(255) DEFAULT NULL, url_github VARCHAR(255) DEFAULT NULL, updated DATETIME DEFAULT NULL, app_id_facebook VARCHAR(50) DEFAULT NULL, show_comments_facebook TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id)');
        $this->addSql('ALTER TABLE post_category ADD CONSTRAINT FK_B9A190604B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_category ADD CONSTRAINT FK_B9A1906012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_post_rel ADD CONSTRAINT FK_A29C747CEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE media_post_rel ADD CONSTRAINT FK_A29C747C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CC54C8C93 FOREIGN KEY (type_id) REFERENCES media_type (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE post_category DROP FOREIGN KEY FK_B9A1906012469DE2');
        $this->addSql('ALTER TABLE post_category DROP FOREIGN KEY FK_B9A190604B89032C');
        $this->addSql('ALTER TABLE media_post_rel DROP FOREIGN KEY FK_A29C747C4B89032C');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CC54C8C93');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C162CB942');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D5DA0FB8');
        $this->addSql('ALTER TABLE media_post_rel DROP FOREIGN KEY FK_A29C747CEA9FDD75');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE post_category');
        $this->addSql('DROP TABLE media_type');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE folder');
        $this->addSql('DROP TABLE media_post_rel');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE setting');
    }
}
