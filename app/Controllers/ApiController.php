<?php

namespace App\Controllers;

use App\Repositories\LicitacaoRepository;
use App\Repositories\AlertaRepository;
use App\Repositories\NotificacaoRepository;
use App\Services\AuthService;

class ApiController extends BaseController
{
    protected $licitacaoRepo;
    protected $alertaRepo;
    protected $notificacaoRepo;
    protected $authService;

    public function __construct()
    {
        $this->licitacaoRepo = new LicitacaoRepository();
        $this->alertaRepo = new AlertaRepository();
        $this->notificacaoRepo = new NotificacaoRepository();
        $this->authService = new AuthService(session());
    }

    public function licitacoes()
    {
        $filters = $this->request->getGet();
        $licitacoes = $this->licitacaoRepo->findByFilters($filters);

        return $this->respond($licitacoes);
    }

    public function licitacao($id)
    {
        $licitacao = $this->licitacaoRepo->findWithOrgaoAndInsight($id);

        if (!$licitacao) {
            return $this->respondError('Licitação não encontrada', 404);
        }

        return $this->respond($licitacao);
    }

    public function estatisticas()
    {
        $estatisticas = [
            'gerais' => $this->licitacaoRepo->getEstatisticasGerais(),
            'situacao' => $this->licitacaoRepo->getDistribuicaoSituacao(),
            'modalidade' => $this->licitacaoRepo->getDistribuicaoModalidade(),
            'orgaos' => $this->licitacaoRepo->getOrgaosMaisAtivos(),
        ];

        return $this->respond($estatisticas);
    }

    public function alertas()
    {
        $this->authService->requireAuth();
        $user = $this->authService->getAuthenticatedUser();

        $alertas = $this->alertaRepo->findByUsuario($user->id);

        return $this->respond($alertas);
    }

    public function criarAlerta()
    {
        $this->authService->requireAuth();
        $user = $this->authService->getAuthenticatedUser();

        $data = $this->request->getJSON(true);
        $data['usuario_id'] = $user->id;

        $id = $this->alertaRepo->create($data);

        return $this->respondSuccess('Alerta criado', ['id' => $id]);
    }

    public function notificacoes()
    {
        $this->authService->requireAuth();
        $user = $this->authService->getAuthenticatedUser();

        $notificacoes = $this->notificacaoRepo->findByUsuario($user->id);

        return $this->respond($notificacoes);
    }
}