<?php

namespace App\Controllers;

use App\Services\LicitacaoService;
use App\Services\AlertaService;
use App\Services\NotificacaoService;
use App\Services\AuthService;

class ApiController extends BaseController
{
    protected LicitacaoService $licitacaoService;
    protected AlertaService $alertaService;
    protected NotificacaoService $notificacaoService;
    protected AuthService $authService;

    public function __construct(?LicitacaoService $licitacaoService=null, ?AlertaService $alertaService=null, ?NotificacaoService $notificacaoService=null, ?AuthService $authService=null)
    {
        $this->licitacaoService = $licitacaoService ?? new LicitacaoService();
        $this->alertaService = $alertaService ?? new AlertaService();
        $this->notificacaoService = $notificacaoService ?? new NotificacaoService(
            new \App\Repositories\AlertaRepository(),
            new \App\Repositories\NotificacaoRepository(),
            new \App\Repositories\LicitacaoRepository()
        );
        $this->authService = $authService ?? new AuthService(session());
    }

    public function licitacoes()
    {
        $filters = $this->request->getGet();
        $licitacoes = $this->licitacaoService->findByFilters($filters);

        return $this->respond($licitacoes);
    }

    public function licitacao($id)
    {
        $licitacao = $this->licitacaoService->findWithOrgaoAndInsight($id);

        if (!$licitacao) {
            return $this->respondError('Licitação não encontrada', 404);
        }

        return $this->respond($licitacao);
    }

    public function estatisticas()
    {
        $estatisticas = [
            'gerais' => $this->licitacaoService->getEstatisticasGerais(),
            'situacao' => $this->licitacaoService->getDistribuicaoSituacao(),
            'modalidade' => $this->licitacaoService->getDistribuicaoModalidade(),
            'orgaos' => $this->licitacaoService->getOrgaosMaisAtivos(),
        ];

        return $this->respond($estatisticas);
    }

    public function alertas()
    {
        $this->authService->requireAuth();
        $user = $this->authService->getAuthenticatedUser();

        $alertas = $this->alertaService->findByUsuario($user->id);

        return $this->respond($alertas);
    }

    public function criarAlerta()
    {
        $this->authService->requireAuth();
        $user = $this->authService->getAuthenticatedUser();

        $data = $this->request->getJSON(true);
        $data['usuario_id'] = $user->id;

        $id = $this->alertaService->create($data);

        return $this->respondSuccess('Alerta criado', ['id' => $id]);
    }

    public function notificacoes()
    {
        $this->authService->requireAuth();
        $user = $this->authService->getAuthenticatedUser();

        $notificacoes = $this->notificacaoService->findByUsuario($user->id);

        return $this->respond($notificacoes);
    }
}