<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603070725 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, participant_a_id INT NOT NULL, participant_b_id INT NOT NULL, INDEX IDX_659DF2AAEF6A4166 (participant_a_id), INDEX IDX_659DF2AAFDDFEE88 (participant_b_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_message (id INT AUTO_INCREMENT NOT NULL, chat_id INT NOT NULL, INDEX IDX_FAB3FC161A9A7125 (chat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAEF6A4166 FOREIGN KEY (participant_a_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAFDDFEE88 FOREIGN KEY (participant_b_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC161A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FEBDEAB20');
        $this->addSql('DROP INDEX IDX_B6BD307FEBDEAB20 ON message');
        $this->addSql('ALTER TABLE message CHANGE user_receive_id chat_message_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F948B568F FOREIGN KEY (chat_message_id) REFERENCES chat_message (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F948B568F ON message (chat_message_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC161A9A7125');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F948B568F');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE chat_message');
        $this->addSql('DROP INDEX IDX_B6BD307F948B568F ON message');
        $this->addSql('ALTER TABLE message CHANGE chat_message_id user_receive_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FEBDEAB20 FOREIGN KEY (user_receive_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B6BD307FEBDEAB20 ON message (user_receive_id)');
    }
}
