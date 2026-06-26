<?php

namespace App\Controllers;

use App\Repositories\NotificacaoRepository;
use App\Services\AuthService;

class NotificacaoController extends BaseController
{
    protected $notificacaoRepo;
    protected $authService;

    public function __construct()
    {
        $this->notificacaoRepo = new NotificacaoRepository();
        $this->authService = new AuthService(session());
    }

    /**
     * Garante que o usuário esteja autenticado e o retorna.
     */
    protected function requireUser()
    {
        $this->authService->requireAuth();
        return $this->authService->getAuthenticatedUser();
    }

    /**
     * Busca a notificação e verifica se pertence ao usuário.
     */
    protected function findNotificacaoOrFail($id, $userId)
    {
        $notificacao = $this->notificacaoRepo->findById($id);

        if (!$notificacao || $notificacao['usuario_id'] != $userId) {
            return null;
        }

        return $notificacao;
    }

    public function index()
    {
        $user = $this->requireUser();
        $notificacoes = $this->notificacaoRepo->findByUsuario($user->id);

        return $this->render('notificacoes/index', ['notificacoes' => $notificacoes]);
    }

    public function marcarLida($id)
    {
        $user = $this->requireUser();
        $notificacao = $this->findNotificacaoOrFail($id, $user->id);

        if (!$notificacao) {
            return $this->respondError('Notificação não encontrada', 404);
        }

        $this->notificacaoRepo->marcarComoLida($id);

        return $this->respondSuccess('Notificação marcada como lida');
    }

    public function marcarTodasLidas()
    {
        $user = $this->requireUser();
        $this->notificacaoRepo->marcarTodasComoLidas($user->id);

        return $this->respondSuccess('Todas as notificações foram marcadas como lidas');
    }

    public function contarNaoLidas()
    {
        $user = $this->requireUser();

        $naoLidas = $this->notificacaoRepo->findNaoLidasByUsuario($user->id);
        $count = count($naoLidas);

        return $this->respondSuccess('Contagem obtida', ['count' => $count]);
    }
}