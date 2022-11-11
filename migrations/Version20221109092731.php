<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221109092731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plat ADD allergen_id INT NOT NULL');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A2076E775A4A FOREIGN KEY (allergen_id) REFERENCES allergen (id)');
        $this->addSql('CREATE INDEX IDX_2038A2076E775A4A ON plat (allergen_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A2076E775A4A');
        $this->addSql('DROP INDEX IDX_2038A2076E775A4A ON plat');
        $this->addSql('ALTER TABLE plat DROP allergen_id');
    }
}
