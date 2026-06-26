<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLicitacoesTable extends Migration
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
            'codigo_pncp' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => false,
                'null'       => false,
            ],
            'orgao_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'objeto' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'modalidade' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'situacao' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'data_publicacao' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'data_abertura' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'data_encerramento' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'valor_estimado' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
            ],
            'link_pncp' => [
                'type'       => 'VARCHAR',
                'constraint' => '500',
                'null'       => true,
            ],
            'unidade_gestora' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'processo' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
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
            'sincronizado_em' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('codigo_pncp', 'unique_codigo_pncp');
        $this->forge->addKey('orgao_id');
        $this->forge->addKey('modalidade');
        $this->forge->addKey('situacao');
        $this->forge->addKey('data_publicacao');
        $this->forge->addKey('valor_estimado');
        $this->forge->addKey('data_abertura');
        $this->forge->addKey('data_encerramento');

        $this->forge->addForeignKey('orgao_id', 'orgaos', 'id', 'RESTRICT', 'RESTRICT');

        $this->forge->createTable('licitacoes');
    }

    public function down()
    {
        $this->forge->dropTable('licitacoes');
    }
}