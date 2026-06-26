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

    public function findByCNPJ(string $cnpj): array|null
    {
        return $this->model->findByCNPJ($cnpj);
    }

    public function findOrCreate(array $data): int|string
    {
        return $this->model->findOrCreate($data);
    }

    public function getByUF(string $uf): array
    {
        return $this->model->where('uf', $uf)->findAll();
    }

    public function searchByNome(string $termo): array
    {
        return $this->model->like('nome', $termo)->findAll();
    }
}