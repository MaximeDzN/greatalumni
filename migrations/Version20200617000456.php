<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617000456 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat (id INT AUTO_INCREMENT NOT NULL, participant_a_id INT NOT NULL, participant_b_id INT NOT NULL, INDEX IDX_659DF2AAEF6A4166 (participant_a_id), INDEX IDX_659DF2AAFDDFEE88 (participant_b_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_message (id INT AUTO_INCREMENT NOT NULL, chat_id INT NOT NULL, INDEX IDX_FAB3FC161A9A7125 (chat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAEF6A4166 FOREIGN KEY (participant_a_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AAFDDFEE88 FOREIGN KEY (participant_b_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat_message ADD CONSTRAINT FK_FAB3FC161A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id)');
        $this->addSql('ALTER TABLE comment ADD is_reported TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FEBDEAB20');
        $this->addSql('DROP INDEX IDX_B6BD307FEBDEAB20 ON message');
        $this->addSql('ALTER TABLE message CHANGE user_receive_id chat_message_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F948B568F FOREIGN KEY (chat_message_id) REFERENCES chat_message (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F948B568F ON message (chat_message_id)');
        $this->addSql('ALTER TABLE news CHANGE media media VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF675F31B');
        $this->addSql('DROP INDEX IDX_5A8A6C8DF675F31B ON post');
        $this->addSql('ALTER TABLE post DROP media, DROP slug, CHANGE author_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
        $this->addSql('ALTER TABLE post_answer DROP FOREIGN KEY FK_EAFFAE40F675F31B');
        $this->addSql('DROP INDEX IDX_EAFFAE40F675F31B ON post_answer');
        $this->addSql('ALTER TABLE post_answer DROP media, CHANGE author_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE post_answer ADD CONSTRAINT FK_EAFFAE40A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EAFFAE40A76ED395 ON post_answer (user_id)');
        $this->addSql('DROP INDEX UNIQ_458B3022989D9B62 ON post_type');
        $this->addSql('ALTER TABLE post_type DROP slug');
        $this->addSql('ALTER TABLE user ADD expression VARCHAR(255) DEFAULT NULL, ADD reset_token VARCHAR(50) DEFAULT NULL, ADD registration_token VARCHAR(50) DEFAULT NULL, ADD hobbie JSON DEFAULT NULL, ADD career JSON DEFAULT NULL, ADD school_curriculum JSON DEFAULT NULL, CHANGE login login VARCHAR(180) DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE nickname nickname VARCHAR(255) DEFAULT NULL, CHANGE department department VARCHAR(255) DEFAULT NULL, CHANGE promo promo VARCHAR(255) DEFAULT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL, CHANGE gender gender INT DEFAULT NULL, CHANGE photo photo VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_message DROP FOREIGN KEY FK_FAB3FC161A9A7125');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F948B568F');
        $this->addSql('DROP TABLE chat');
        $this->addSql('DROP TABLE chat_message');
        $this->addSql('ALTER TABLE comment DROP is_reported');
        $this->addSql('DROP INDEX IDX_B6BD307F948B568F ON message');
        $this->addSql('ALTER TABLE message CHANGE chat_message_id user_receive_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FEBDEAB20 FOREIGN KEY (user_receive_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FEBDEAB20 ON message (user_receive_id)');
        $this->addSql('ALTER TABLE news CHANGE media media VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('DROP INDEX IDX_5A8A6C8DA76ED395 ON post');
        $this->addSql('ALTER TABLE post ADD media VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, ADD slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE user_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF675F31B ON post (author_id)');
        $this->addSql('ALTER TABLE post_answer DROP FOREIGN KEY FK_EAFFAE40A76ED395');
        $this->addSql('DROP INDEX IDX_EAFFAE40A76ED395 ON post_answer');
        $this->addSql('ALTER TABLE post_answer ADD media VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE user_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE post_answer ADD CONSTRAINT FK_EAFFAE40F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_EAFFAE40F675F31B ON post_answer (author_id)');
        $this->addSql('ALTER TABLE post_type ADD slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_458B3022989D9B62 ON post_type (slug)');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user DROP expression, DROP reset_token, DROP registration_token, DROP hobbie, DROP career, DROP school_curriculum, CHANGE login login VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE password password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE nickname nickname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE department department VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE promo promo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE gender gender INT NOT NULL, CHANGE photo photo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
