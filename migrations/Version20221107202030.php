<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221107202030 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allergen_plat (id INT AUTO_INCREMENT NOT NULL, plat_id_id INT NOT NULL, allergen_id_id INT NOT NULL, INDEX IDX_C87DC583EF4C182B (plat_id_id), INDEX IDX_C87DC58395799C09 (allergen_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE allergen_plat ADD CONSTRAINT FK_C87DC583EF4C182B FOREIGN KEY (plat_id_id) REFERENCES plat (id)');
        $this->addSql('ALTER TABLE allergen_plat ADD CONSTRAINT FK_C87DC58395799C09 FOREIGN KEY (allergen_id_id) REFERENCES allergen (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE allergen_plat DROP FOREIGN KEY FK_C87DC583EF4C182B');
        $this->addSql('ALTER TABLE allergen_plat DROP FOREIGN KEY FK_C87DC58395799C09');
        $this->addSql('DROP TABLE allergen_plat');
    }
}
