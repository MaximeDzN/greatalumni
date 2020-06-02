<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200602092330 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_send_id INT NOT NULL, user_receive_id INT NOT NULL, content LONGTEXT NOT NULL, send_date DATETIME NOT NULL, INDEX IDX_B6BD307F4B9E2071 (user_send_id), INDEX IDX_B6BD307FEBDEAB20 (user_receive_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F4B9E2071 FOREIGN KEY (user_send_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FEBDEAB20 FOREIGN KEY (user_receive_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE private_messages');
        $this->addSql('ALTER TABLE news CHANGE media media VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE private_messages (id INT AUTO_INCREMENT NOT NULL, emitter INT DEFAULT NULL, receiver INT DEFAULT NULL, message LONGTEXT CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, file VARCHAR(255) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`, image VARCHAR(255) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`, readed VARCHAR(3) CHARACTER SET latin1 DEFAULT \'NULL\' COLLATE `latin1_swedish_ci`, created_at DATETIME DEFAULT \'NULL\', INDEX fk_emmiter_privates (emitter), INDEX fk_receiver_privates (receiver), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE private_messages ADD CONSTRAINT fk_emmiter_privates FOREIGN KEY (emitter) REFERENCES user (id)');
        $this->addSql('ALTER TABLE private_messages ADD CONSTRAINT fk_receiver_privates FOREIGN KEY (receiver) REFERENCES user (id)');
        $this->addSql('DROP TABLE message');
        $this->addSql('ALTER TABLE news CHANGE media media VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE photo photo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
