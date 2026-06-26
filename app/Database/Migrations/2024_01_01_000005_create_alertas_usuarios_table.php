<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAlertasUsuariosTable extends Migration
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
            'nome' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'palavras_chave' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'modalidades' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'valor_minimo' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null'       => true,
            ],
            'valor_maximo' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'null' => true,
            ],
            'situacoes' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'ufs' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'frequencia' => [
                'type' => 'ENUM',
                'constraint' => ['imediato', 'diario', 'semanal'],
                'default' => 'diario',
                'null' => false,
            ],
            'ativo' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'null' => false,
            ],
            'ultima_notificacao' => [
                'type' => 'TIMESTAMP',
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
        $this->forge->addKey('usuario_id');
        $this->forge->addKey('ativo');
        $this->forge->addKey('frequencia');

        $this->forge->addForeignKey('usuario_id', 'users', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('alertas_usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('alertas_usuarios');
    }
}