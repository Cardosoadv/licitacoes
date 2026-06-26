<?php

namespace App\Controllers;

use App\Repositories\LicitacaoRepository;
use App\Repositories\OrgaoRepository;
use App\Repositories\InsightRepository;
use App\Services\InsightService;

class LicitacaoController extends BaseController
{
    protected $licitacaoRepo;
    protected $orgaoRepo;
    protected $insightRepo;
    protected $insightService;

    public function __construct()
    {
        $this->licitacaoRepo = new LicitacaoRepository();
        $this->orgaoRepo = new OrgaoRepository();
        $this->insightRepo = new InsightRepository();
        $this->insightService = new InsightService($this->insightRepo);
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