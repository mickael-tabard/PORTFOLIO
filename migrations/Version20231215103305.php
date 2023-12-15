<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215103305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE likes ADD tweet_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id)');
        $this->addSql('CREATE INDEX IDX_49CA4E7D1041E39B ON likes (tweet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7D1041E39B');
        $this->addSql('DROP INDEX IDX_49CA4E7D1041E39B ON likes');
        $this->addSql('ALTER TABLE likes DROP tweet_id');
    }
}
