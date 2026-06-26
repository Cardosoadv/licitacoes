<?php

namespace App\Repositories;

use App\Models\NotificacaoModel;

class NotificacaoRepository extends BaseRepository
{
    protected $table = 'notificacoes';

    public function __construct()
    {
        $this->model = new NotificacaoModel();
    }

    public function findNaoLidasByUsuario(int $usuarioId): array
    {
        return $this->model->where('usuario_id', $usuarioId)->where('lida', false)->findAll();
    }

    public function marcarComoLida(int $id): bool
    {
        return $this->model->update($id, ['lida' => true]);
    }

    public function marcarTodasComoLidas(int $usuarioId): bool
    {
        return $this->model->where('usuario_id', $usuarioId)->set(['lida' => true])->update();
    }

    public function findByUsuario(int $usuarioId, int $limit = 50): array
    {
        return $this->model->where('usuario_id', $usuarioId)->orderBy('created_at', 'DESC')->findAll($limit);
    }
}