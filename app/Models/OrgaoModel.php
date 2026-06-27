<?php

namespace App\Models;

use CodeIgniter\Model;

class OrgaoModel extends Model
{
    protected $table = 'orgaos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['cnpj', 'nome', 'nome_resumido', 'esfera', 'poder', 'uf', 'municipio'];
    protected $useTimestamps = true;
    protected $validationRules = [
        'cnpj' => 'required|max_length[18]',
        'nome' => 'required',
    ];

    public function findByCNPJ($cnpj)
    {
        return $this->where('cnpj', $cnpj)->first();
    }

    public function findOrCreate($data)
    {
        $orgao = $this->findByCNPJ($data['cnpj']);
        if (!$orgao) {
            $this->insert($data);
            return $this->findByCNPJ($data['cnpj']);
        }
        return $orgao;
    }
}