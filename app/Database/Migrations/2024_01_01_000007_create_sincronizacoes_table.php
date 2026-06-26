<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSincronizacoesTable extends Migration
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
            'tipo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'data_inicio' => [
                'type' => 'TIMESTAMP',
                'null' => false,
            ],
            'data_fim' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['iniciado', 'processando', 'concluido', 'erro'],
                'default' => 'iniciado',
                'null' => false,
            ],
            'total_buscados' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
            ],
            'total_novos' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
            ],
            'total_atualizados' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
            ],
            'total_erros' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'null'       => false,
            ],
            'mensagem_erro' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'detalhes' => [
                'type' => 'JSON',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('data_inicio');
        $this->forge->addKey('status');

        $this->forge->createTable('sincronizacoes');
    }

    public function down()
    {
        $this->forge->dropTable('sincronizacoes');
    }
}