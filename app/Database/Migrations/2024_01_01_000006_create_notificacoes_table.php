<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificacoesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'usuario_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'alerta_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'licitacao_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'titulo' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'mensagem' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'lida' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'null' => false,
            ],
            'enviada_em' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('usuario_id');
        $this->forge->addKey('lida');
        $this->forge->addKey('created_at');

        $this->forge->addForeignKey('usuario_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('alerta_id', 'alertas_usuarios', 'id', 'SET NULL', 'SET NULL');
        $this->forge->addForeignKey('licitacao_id', 'licitacoes', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('notificacoes');
    }

    public function down()
    {
        $this->forge->dropTable('notificacoes');
    }
}