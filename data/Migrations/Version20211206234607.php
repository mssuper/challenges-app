<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20211206234607 extends AbstractMigration
{
    public function getDescription()
    {
        $description = 'Esta é a migração de tabelas de salas.';
        return $description;
    }
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // esta migração up() é gerada automaticamente, modifique-a de acordo com suas necessidades
        $table = $schema->createTable('rooms');
        $table->addColumn('idroom', 'integer', ['autoincrement' => true]);
        $table->addColumn('area', 'integer', ['notnull' => true]);
        $table->addColumn('room_name', 'string', ['notnull' => true, 'length' => 128]);
        $table->addColumn('status', 'integer', ['notnull' => true]);
        $table->addColumn('date_created', 'datetime', ['notnull' => true]);
        $table->setPrimaryKey(['idroom']);
        $table->addUniqueIndex(['room_name'], 'room_name_idx');
        $table->addOption('engine', 'InnoDB');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $schema->dropTable('rooms');
    }
}
