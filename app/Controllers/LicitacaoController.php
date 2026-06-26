<?php

namespace App\Controllers;

use App\Repositories\LicitacaoRepository;
use App\Repositories\OrgaoRepository;
use App\Repositories\InsightRepository;
use App\Services\InsightService;

class LicitacaoController extends BaseController
{
    protected LicitacaoRepository $licitacaoRepo; //TODO: Substituir pelo Service. Sem chamada direta a repositorio no controller. (codigo não limpo)
    protected OrgaoRepository $orgaoRepo; //TODO: Substituir pelo Service. Sem chamada direta a repositorio no controller. (codigo não limpo)
    protected InsightRepository $insightRepo; //TODO: Substituir pelo Service. Sem chamada direta a repositorio no controller. (codigo não limpo)
    protected InsightService $insightService; //TODO: Substituir pelo Service. Sem chamada direta a repositorio no controller. (codigo não limpo)

    public function __construct(?LicitacaoRepository $licitacaoRepo=null, ?OrgaoRepository $orgaoRepo=null, ?InsightRepository $insightRepo=null, ?InsightService $insightService=null)
    {
        $this->licitacaoRepo = $licitacaoRepo ?? new LicitacaoRepository();
        $this->orgaoRepo = $orgaoRepo ?? new OrgaoRepository();
        $this->insightRepo = $insightRepo ?? new InsightRepository();
        $this->insightService = $insightService ?? new InsightService($this->insightRepo);
    }

    public function index()
    {
        $stats = [
            'gerais' => $this->licitacaoRepo->getEstatisticasGerais(),
            'situacao' => $this->licitacaoRepo->getDistribuicaoSituacao(),
            'modalidade' => $this->licitacaoRepo->getDistribuicaoModalidade(),
            'orgaos' => $this->licitacaoRepo->getOrgaosMaisAtivos(5),
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

        $licitacoes = $this->licitacaoRepo->findByFilters($filters, $perPage, $page);

        return $this->render('licitacoes/listar', [
            'licitacoes' => $licitacoes,
            'pager' => $this->licitacaoRepo->getPager(),
            'filters' => $filters
        ]);
    }

    public function detalhes($id)
    {
        $licitacao = $this->licitacaoRepo->findWithOrgaoAndInsight($id);

        if (!$licitacao) {
            return $this->respondError('Licitação não encontrada', 404);
        }

        return $this->render('licitacoes/detalhes', ['licitacao' => $licitacao]);
    }

    public function buscar()
    {
        $termo = $this->request->getGet('q');
        $licitacoes = $this->licitacaoRepo->findByFilters(['termo' => $termo]);

        return $this->respondSuccess('Busca realizada', $licitacoes);
    }

    public function filtrar()
    {
        $filters = $this->request->getJSON(true);
        $licitacoes = $this->licitacaoRepo->findByFilters($filters);

        return $this->respondSuccess('Filtro aplicado', $licitacoes);
    }

    public function estatisticas()
    {
        $estatisticas = [
            'gerais' => $this->licitacaoRepo->getEstatisticasGerais(),
            'situacao' => $this->licitacaoRepo->getDistribuicaoSituacao(),
            'modalidade' => $this->licitacaoRepo->getDistribuicaoModalidade(),
            'orgaos' => $this->licitacaoRepo->getOrgaosMaisAtivos(),
        ];

        return $this->respondSuccess('Estatísticas obtidas', $estatisticas);
    }

    public function exportarCSV()
    {
        $filters = $this->request->getGet();
        $licitacoes = $this->licitacaoRepo->findByFilters($filters, 1000);

        // Implementar exportação CSV
        return $this->respondSuccess('Exportação iniciada');
    }
}