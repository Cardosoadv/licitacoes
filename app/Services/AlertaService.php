<?php

namespace App\Services;

use App\Repositories\AlertaRepository;

class AlertaService
{
    protected AlertaRepository $alertaRepo;

    public function __construct(?AlertaRepository $alertaRepo = null)
    {
        $this->alertaRepo = $alertaRepo ?? new AlertaRepository();
    }

    public function findById($id)
    {
        return $this->alertaRepo->findById($id);
    }

    public function findByUsuario($userId)
    {
        return $this->alertaRepo->findByUsuario($userId);
    }

    public function create($data)
    {
        return $this->alertaRepo->create($data);
    }

    public function update($id, $data)
    {
        return $this->alertaRepo->update($id, $data);
    }

    public function delete($id)
    {
        return $this->alertaRepo->delete($id);
    }
}
