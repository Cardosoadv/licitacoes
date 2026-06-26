<?php

namespace App\Repositories;

use App\Models\AlertaModel;

class AlertaRepository extends BaseRepository
{
    protected $table = 'alertas_usuarios';

    public function __construct()
    {
        $this->model = new AlertaModel();
    }

    public function findByUsuario(int $usuarioId): array
    {
        return $this->model->where('usuario_id', $usuarioId)->findAll();
    }

    public function findActiveByUsuario(int $usuarioId): array
    {
        return $this->model->where('usuario_id', $usuarioId)->where('ativo', true)->findAll();
    }

    public function findAlertasParaNotificacao(array $licitacao): array
    {
        // Implementar lógica complexa para matching de alertas
        // Por simplicidade, retornar todos ativos por enquanto
        return $this->findActiveByUsuario(0); // Ajustar conforme necessário
    }

    public function updateUltimaNotificacao(int $alertaId): bool
    {
        return $this->model->update($alertaId, ['ultima_notificacao' => date('Y-m-d H:i:s')]);
    }
}