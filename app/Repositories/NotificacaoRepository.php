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

    public function findNaoLidasByUsuario($usuarioId)
    {
        return $this->model->where('usuario_id', $usuarioId)->where('lida', false)->findAll();
    }

    public function marcarComoLida($id)
    {
        return $this->model->update($id, ['lida' => true]);
    }

    public function marcarTodasComoLidas($usuarioId)
    {
        return $this->model->where('usuario_id', $usuarioId)->set(['lida' => true])->update();
    }

    public function findByUsuario($usuarioId, $limit = 50)
    {
        return $this->model->where('usuario_id', $usuarioId)->orderBy('created_at', 'DESC')->findAll($limit);
    }
}