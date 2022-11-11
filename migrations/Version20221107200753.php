<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221107200753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE menu_plat (id INT AUTO_INCREMENT NOT NULL, menu_id_id INT NOT NULL, plat_id_id INT NOT NULL, INDEX IDX_E8775249EEE8BD30 (menu_id_id), INDEX IDX_E8775249EF4C182B (plat_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_plat ADD CONSTRAINT FK_E8775249EEE8BD30 FOREIGN KEY (menu_id_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE menu_plat ADD CONSTRAINT FK_E8775249EF4C182B FOREIGN KEY (plat_id_id) REFERENCES plat (id)');
        $this->addSql('ALTER TABLE menu ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A939D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_7D053A939D86650F ON menu (user_id_id)');
        $this->addSql('ALTER TABLE plat ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A2079D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2038A2079D86650F ON plat (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_plat DROP FOREIGN KEY FK_E8775249EEE8BD30');
        $this->addSql('ALTER TABLE menu_plat DROP FOREIGN KEY FK_E8775249EF4C182B');
        $this->addSql('DROP TABLE menu_plat');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A939D86650F');
        $this->addSql('DROP INDEX IDX_7D053A939D86650F ON menu');
        $this->addSql('ALTER TABLE menu DROP user_id_id');
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A2079D86650F');
        $this->addSql('DROP INDEX IDX_2038A2079D86650F ON plat');
        $this->addSql('ALTER TABLE plat DROP user_id_id');
    }
}
