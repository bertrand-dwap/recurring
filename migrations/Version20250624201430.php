<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250624201430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE linked_file (id INT AUTO_INCREMENT NOT NULL, recurring_task_id INT NOT NULL, original_name VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, uniqid VARCHAR(255) NOT NULL, INDEX IDX_58E8E36A5540940F (recurring_task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, recurring_task_id INT NOT NULL, date DATE NOT NULL, comments LONGTEXT DEFAULT NULL, INDEX IDX_8F3F68C55540940F (recurring_task_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE recurring_task (id INT AUTO_INCREMENT NOT NULL, task LONGTEXT NOT NULL, frequency INT DEFAULT NULL, frequency_unit VARCHAR(255) DEFAULT NULL, next_time DATE DEFAULT NULL, nb_days_before_to_display INT DEFAULT 0 NOT NULL, can_procrastinate TINYINT(1) DEFAULT 0 NOT NULL, end DATE DEFAULT NULL, comments LONGTEXT DEFAULT NULL, latest_operations_visible TINYINT(1) DEFAULT 0 NOT NULL, procrastinated DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE linked_file ADD CONSTRAINT FK_58E8E36A5540940F FOREIGN KEY (recurring_task_id) REFERENCES recurring_task (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE log ADD CONSTRAINT FK_8F3F68C55540940F FOREIGN KEY (recurring_task_id) REFERENCES recurring_task (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE linked_file DROP FOREIGN KEY FK_58E8E36A5540940F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE log DROP FOREIGN KEY FK_8F3F68C55540940F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE linked_file
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE log
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE recurring_task
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
