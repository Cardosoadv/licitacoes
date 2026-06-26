<?php

namespace App\Repositories;

use App\Models\LicitacaoModel;

class LicitacaoRepository extends BaseRepository
{
    protected $table = 'licitacoes';

    public function __construct()
    {
        $this->model = new LicitacaoModel();
    }

    public function findWithOrgaoAndInsight($id)
    {
        return $this->model->withOrgao()->withInsight()->find($id);
    }

    public function findByFilters($filters, $perPage = 20, $page = 1)
    {
        $query = $this->model;

        if (!empty($filters['termo'])) {
            $query = $query->search($filters['termo']);
        }

        if (!empty($filters['modalidades'])) {
            $query = $query->filterByModalidade($filters['modalidades']);
        }

        if (!empty($filters['situacoes'])) {
            $query = $query->filterBySituacao($filters['situacoes']);
        }

        if (!empty($filters['data_inicio']) || !empty($filters['data_fim'])) {
            $query = $query->filterByPeriodo($filters['data_inicio'] ?? null, $filters['data_fim'] ?? null);
        }

        if (isset($filters['valor_min']) || isset($filters['valor_max'])) {
            $query = $query->filterByValor($filters['valor_min'] ?? null, $filters['valor_max'] ?? null);
        }

        return $query->paginate($perPage, 'default', $page);
    }

    public function getEstatisticasGerais()
    {
        return $this->model->select('COUNT(*) as total, SUM(valor_estimado) as valor_total')
                          ->first();
    }

    public function getDistribuicaoSituacao()
    {
        return $this->model->select('situacao, COUNT(*) as quantidade')
                          ->groupBy('situacao')
                          ->findAll();
    }

    public function getDistribuicaoModalidade()
    {
        return $this->model->select('modalidade, COUNT(*) as quantidade')
                          ->groupBy('modalidade')
                          ->findAll();
    }

    public function getOrgaosMaisAtivos($limit = 10)
    {
        return $this->model->select('orgaos.nome, COUNT(*) as quantidade')
                          ->join('orgaos', 'orgaos.id = licitacoes.orgao_id')
                          ->groupBy('orgao_id')
                          ->orderBy('quantidade', 'DESC')
                          ->findAll($limit);
    }

    public function findNovasLicitacoes($since = null)
    {
        $query = $this->model->where('created_at >', $since ?? date('Y-m-d H:i:s', strtotime('-7 days')));
        return $query->findAll();
    }

    public function updateByCodigoPNCP($codigo, $data)
    {
        return $this->model->where('codigo_pncp', $codigo)->set($data)->update();
    }

    public function findByCodigoPNCP($codigo)
    {
        return $this->model->where('codigo_pncp', $codigo)->first();
    }
}