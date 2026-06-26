<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrgaosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'cnpj' => '00394462000104',
                'nome' => 'Ministério da Economia',
                'esfera' => 'FEDERAL',
                'poder' => 'EXECUTIVO',
                'uf' => 'DF',
            ],
            [
                'cnpj' => '00394508000107',
                'nome' => 'Ministério da Saúde',
                'esfera' => 'FEDERAL',
                'poder' => 'EXECUTIVO',
                'uf' => 'DF',
            ],
            [
                'cnpj' => '00394650000118',
                'nome' => 'Ministério da Educação',
                'esfera' => 'FEDERAL',
                'poder' => 'EXECUTIVO',
                'uf' => 'DF',
            ],
        ];

        $this->db->table('orgaos')->insertBatch($data);
    }
}