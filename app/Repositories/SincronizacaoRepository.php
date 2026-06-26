<?php

namespace App\Repositories;

use App\Models\SincronizacaoModel;

class SincronizacaoRepository extends BaseRepository
{
    protected $table = 'sincronizacoes';

    public function __construct()
    {
        $this->model = new SincronizacaoModel();
    }

    public function findLastSync(string $tipo = 'licitacoes'): array|null
    {
        return $this->model->where('tipo', $tipo)->orderBy('data_inicio', 'DESC')->first();
    }

    public function startSync(string $tipo): int|string
    {
        return $this->model->insert([
            'tipo' => $tipo,
            'data_inicio' => date('Y-m-d H:i:s'),
            'status' => 'iniciado'
        ]);
    }

    public function endSync(int $id, string $status, array $data): bool
    {
        $data['data_fim'] = date('Y-m-d H:i:s');
        $data['status'] = $status;
        return $this->model->update($id, $data);
    }

    public function logError(int $id, string $message): bool
    {
        return $this->model->update($id, [
            'status' => 'erro',
            'mensagem_erro' => $message,
            'data_fim' => date('Y-m-d H:i:s')
        ]);
    }
}