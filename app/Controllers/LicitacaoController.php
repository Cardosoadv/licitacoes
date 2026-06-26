<?php

namespace App\Controllers;

use App\Services\LicitacaoService;
use App\Services\OrgaoService;
use App\Services\InsightService;

class LicitacaoController extends BaseController
{
    protected LicitacaoService $licitacaoService;
    protected OrgaoService $orgaoService;
    protected InsightService $insightService;

    public function __construct(?LicitacaoService $licitacaoService=null, ?OrgaoService $orgaoService=null, ?InsightService $insightService=null)
    {
        $this->licitacaoService = $licitacaoService ?? new LicitacaoService();
        $this->orgaoService = $orgaoService ?? new OrgaoService();
        $this->insightService = $insightService ?? new InsightService(new \App\Repositories\InsightRepository());
    }


    public function index()
    {
        $stats = [
            'gerais' => $this->licitacaoService->getEstatisticasGerais(),
            'situacao' => $this->licitacaoService->getDistribuicaoSituacao(),
            'modalidade' => $this->licitacaoService->getDistribuicaoModalidade(),
            'orgaos' => $this->licitacaoService->getOrgaosMaisAtivos(5),
        ];

        return $this->render('licitacoes/index', [
            'stats' => $stats,
            'title' => 'Dashboard de Licitações'
        ]);
    }

    public function listar()
    {
        $filters = $this->request->getGet();
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        $licitacoes = $this->licitacaoService->findByFilters($filters, $perPage, $page);

        return $this->render('licitacoes/listar', [
            'licitacoes' => $licitacoes,
            'pager' => $this->licitacaoService->getPager(),
            'filters' => $filters
        ]);
    }

    public function detalhes($id)
    {
        $licitacao = $this->licitacaoService->findWithOrgaoAndInsight($id);

        if (!$licitacao) {
            return $this->respondError('Licitação não encontrada', 404);
        }

        return $this->render('licitacoes/detalhes', ['licitacao' => $licitacao]);
    }

    public function buscar()
    {
        $termo = $this->request->getGet('q');
        $licitacoes = $this->licitacaoService->findByFilters(['termo' => $termo]);

        return $this->respondSuccess('Busca realizada', $licitacoes);
    }

    public function filtrar()
    {
        $filters = $this->request->getJSON(true);
        $licitacoes = $this->licitacaoService->findByFilters($filters);

        return $this->respondSuccess('Filtro aplicado', $licitacoes);
    }

    public function estatisticas()
    {
        $estatisticas = [
            'gerais' => $this->licitacaoService->getEstatisticasGerais(),
            'situacao' => $this->licitacaoService->getDistribuicaoSituacao(),
            'modalidade' => $this->licitacaoService->getDistribuicaoModalidade(),
            'orgaos' => $this->licitacaoService->getOrgaosMaisAtivos(),
        ];

        return $this->respondSuccess('Estatísticas obtidas', $estatisticas);
    }

    public function exportarCSV()
    {
        $filters = $this->request->getGet();
        $licitacoes = $this->licitacaoService->findByFilters($filters, 1000);

        // Implementar exportação CSV
        return $this->respondSuccess('Exportação iniciada');
    }
}