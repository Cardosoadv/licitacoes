<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInsightsLicitacoesTable extends Migration
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
            'licitacao_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'unique'     => false,
                'null'       => false,
            ],
            'resumo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'palavras_chave' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'setor' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'oportunidade_score' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'analise_completa' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('licitacao_id', 'unique_licitacao_id');
        $this->forge->addKey('setor');
        $this->forge->addKey('oportunidade_score');

        $this->forge->addForeignKey('licitacao_id', 'licitacoes', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('insights_licitacoes');
    }

    public function down()
    {
        $this->forge->dropTable('insights_licitacoes');
    }
}