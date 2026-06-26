<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrgaosTable extends Migration
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
            'cnpj' => [
                'type'       => 'VARCHAR',
                'constraint' => '18',
                'unique'     => false,
                'null'       => false,
            ],
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'nome_resumido' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'esfera' => [
                'type' => 'ENUM',
                'constraint' => ['FEDERAL', 'ESTADUAL', 'MUNICIPAL', 'DISTRITAL'],
                'null' => true,
            ],
            'poder' => [
                'type' => 'ENUM',
                'constraint' => ['EXECUTIVO', 'LEGISLATIVO', 'JUDICIARIO'],
                'null' => true,
            ],
            'uf' => [
                'type'       => 'CHAR',
                'constraint' => '2',
                'null'       => true,
            ],
            'municipio' => [
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
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('cnpj', 'unique_cnpj');
        $this->forge->addKey('nome');
        $this->forge->addKey('uf');

        $this->forge->createTable('orgaos');
    }

    public function down()
    {
        $this->forge->dropTable('orgaos');
    }
}