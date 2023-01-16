<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230112192848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client_order_plat (client_order_id INT NOT NULL, plat_id INT NOT NULL, INDEX IDX_FD9DA551A3795DFD (client_order_id), INDEX IDX_FD9DA551D73DB560 (plat_id), PRIMARY KEY(client_order_id, plat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client_order_plat ADD CONSTRAINT FK_FD9DA551A3795DFD FOREIGN KEY (client_order_id) REFERENCES client_order (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_order_plat ADD CONSTRAINT FK_FD9DA551D73DB560 FOREIGN KEY (plat_id) REFERENCES plat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_order ADD table_commande_id INT NOT NULL, ADD serveur_id INT NOT NULL');
        $this->addSql('ALTER TABLE client_order ADD CONSTRAINT FK_56440F2FB1B4DDE9 FOREIGN KEY (table_commande_id) REFERENCES client_table (id)');
        $this->addSql('ALTER TABLE client_order ADD CONSTRAINT FK_56440F2FB8F06499 FOREIGN KEY (serveur_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_56440F2FB1B4DDE9 ON client_order (table_commande_id)');
        $this->addSql('CREATE INDEX IDX_56440F2FB8F06499 ON client_order (serveur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_order_plat DROP FOREIGN KEY FK_FD9DA551A3795DFD');
        $this->addSql('ALTER TABLE client_order_plat DROP FOREIGN KEY FK_FD9DA551D73DB560');
        $this->addSql('DROP TABLE client_order_plat');
        $this->addSql('ALTER TABLE client_order DROP FOREIGN KEY FK_56440F2FB1B4DDE9');
        $this->addSql('ALTER TABLE client_order DROP FOREIGN KEY FK_56440F2FB8F06499');
        $this->addSql('DROP INDEX IDX_56440F2FB1B4DDE9 ON client_order');
        $this->addSql('DROP INDEX IDX_56440F2FB8F06499 ON client_order');
        $this->addSql('ALTER TABLE client_order DROP table_commande_id, DROP serveur_id');
    }
}
