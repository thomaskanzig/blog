<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200606100812 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added more sidebar properties for homepage table';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE homepage ADD sidebar_about_me_url_facebook VARCHAR(255) DEFAULT NULL, ADD sidebar_about_me_url_instagram VARCHAR(255) DEFAULT NULL, ADD sidebar_about_me_url_github VARCHAR(255) DEFAULT NULL, ADD sidebar_about_me_url_youtube VARCHAR(255) DEFAULT NULL, ADD sidebar_about_me_url_linkedin VARCHAR(255) DEFAULT NULL, ADD sidebar_about_me_active TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE homepage DROP sidebar_about_me_url_facebook, DROP sidebar_about_me_url_instagram, DROP sidebar_about_me_url_github, DROP sidebar_about_me_url_youtube, DROP sidebar_about_me_url_linkedin, DROP sidebar_about_me_active');
    }
}
