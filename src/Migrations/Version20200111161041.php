<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200111161041 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media_post_rel CHANGE post_id post_id INT DEFAULT NULL, CHANGE media_id media_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE media_post_rel ADD CONSTRAINT FK_A29C747CEA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('CREATE INDEX IDX_A29C747CEA9FDD75 ON media_post_rel (media_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media_post_rel DROP FOREIGN KEY FK_A29C747CEA9FDD75');
        $this->addSql('DROP INDEX IDX_A29C747CEA9FDD75 ON media_post_rel');
        $this->addSql('ALTER TABLE media_post_rel CHANGE media_id media_id INT NOT NULL, CHANGE post_id post_id INT NOT NULL');
    }
}
