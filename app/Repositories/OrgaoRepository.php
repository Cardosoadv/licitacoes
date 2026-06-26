<?php

namespace App\Repositories;

use App\Models\OrgaoModel;

class OrgaoRepository extends BaseRepository
{
    protected $table = 'orgaos';

    public function __construct()
    {
        $this->model = new OrgaoModel();
    }

    public function findByCNPJ($cnpj)
    {
        return $this->model->findByCNPJ($cnpj);
    }

    public function findOrCreate($data)
    {
        return $this->model->findOrCreate($data);
    }

    public function getByUF($uf)
    {
        return $this->model->where('uf', $uf)->findAll();
    }

    public function searchByNome($termo)
    {
        return $this->model->like('nome', $termo)->findAll();
    }
}