<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Uma Classeaula de migração. Ele atualiza o esquema do banco de dados (o move para um novo estado)
 * ou faz o downgrade para o estado anterior.
 */
class Version20210609241621 extends AbstractMigration
{
    /**
     * Retorna a descrição desta migração.
     */
    public function getDescription()
    {
        $description = 'Esta é a migração inicial que cria a tabela do usuário.';
        return $description;
    }

    /**
     * Atualiza o esquema para seu estado mais recente.
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Criar tabela de 'usuário'
        $table = $schema->createTable('user');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('email', 'string', ['notnull' => true, 'length' => 128]);
        $table->addColumn('full_name', 'string', ['notnull' => true, 'length' => 512]);
        $table->addColumn('password', 'string', ['notnull' => true, 'length' => 256]);
        $table->addColumn('status', 'integer', ['notnull' => true]);
        $table->addColumn('date_created', 'datetime', ['notnull' => true]);
        $table->addColumn('pwd_reset_token', 'string', ['notnull' => false, 'length' => 256]);
        $table->addColumn('pwd_reset_token_creation_date', 'datetime', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['email'], 'email_idx');
        $table->addOption('engine', 'InnoDB');
    }

    /**
     * Reverte as alterações do esquema.
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('user');
    }
}
