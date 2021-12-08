<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20211207180616 extends AbstractMigration
{
    public function getDescription()
    {
        $description = 'Esta é a migração de tabelas de agendamento de salas.';
        return $description;
    }
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $table = $schema->createTable('schedulerooms');
        $table->addColumn('idscheduleroom', 'integer', ['autoincrement' => true]);
        $table->addColumn('datetime_in', 'datetime', ['notnull' => true]);
        $table->addColumn('datetime_out', 'datetime', ['notnull' => true]);
        $table->addColumn('iduser', 'integer', ['notnull' => true]);
        $table->addColumn('idroom', 'integer', ['notnull' => true]);
        $table->setPrimaryKey(['idscheduleroom']);
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('schedulerooms');// this down() migration is auto-generated, please modify it to your needs

    }
}
