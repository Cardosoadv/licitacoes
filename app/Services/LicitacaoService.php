<?php

namespace App\Services;

use App\Repositories\LicitacaoRepository;

class LicitacaoService
{
    protected LicitacaoRepository $licitacaoRepo;

    public function __construct(?LicitacaoRepository $licitacaoRepo = null)
    {
        $this->licitacaoRepo = $licitacaoRepo ?? new LicitacaoRepository();
    }

    public function getEstatisticasGerais()
    {
        return $this->licitacaoRepo->getEstatisticasGerais();
    }

    public function getDistribuicaoSituacao()
    {
        return $this->licitacaoRepo->getDistribuicaoSituacao();
    }

    public function getDistribuicaoModalidade()
    {
        return $this->licitacaoRepo->getDistribuicaoModalidade();
    }

    public function getOrgaosMaisAtivos($limit = 10)
    {
        return $this->licitacaoRepo->getOrgaosMaisAtivos($limit);
    }

    public function findByFilters($filters, $perPage = 0, $page = 1)
    {
        return $this->licitacaoRepo->findByFilters($filters, $perPage, $page);
    }

    public function getPager()
    {
        return $this->licitacaoRepo->getPager();
    }

    public function findWithOrgaoAndInsight($id)
    {
        return $this->licitacaoRepo->findWithOrgaoAndInsight($id);
    }
}
