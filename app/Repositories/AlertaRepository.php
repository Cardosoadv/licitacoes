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

    public function findByUsuario($usuarioId)
    {
        return $this->model->where('usuario_id', $usuarioId)->findAll();
    }

    public function findActiveByUsuario($usuarioId)
    {
        return $this->model->where('usuario_id', $usuarioId)->where('ativo', true)->findAll();
    }

    public function findAlertasParaNotificacao($licitacao)
    {
        // Implementar lógica complexa para matching de alertas
        // Por simplicidade, retornar todos ativos por enquanto
        return $this->findActiveByUsuario(null); // Ajustar conforme necessário
    }

    public function updateUltimaNotificacao($alertaId)
    {
        return $this->model->update($alertaId, ['ultima_notificacao' => date('Y-m-d H:i:s')]);
    }
}