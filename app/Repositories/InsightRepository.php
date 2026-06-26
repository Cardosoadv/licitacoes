<?php

namespace App\Repositories;

use App\Models\InsightModel;

class InsightRepository extends BaseRepository
{
    protected $table = 'insights_licitacoes';

    public function __construct()
    {
        $this->model = new InsightModel();
    }

    public function findByLicitacaoId(int $licitacaoId): array | null
    {
        return $this->model->where('licitacao_id', $licitacaoId)->first();
    }

    public function saveOrUpdate(int $licitacaoId, array $insightData): bool
    {
        $existing = $this->findByLicitacaoId($licitacaoId);
        if ($existing) {
            return $this->model->update($existing['id'], $insightData);
        } else {
            $insightData['licitacao_id'] = $licitacaoId;
            return $this->model->insert($insightData);
        }
    }

    public function deleteByLicitacaoId(int $licitacaoId): bool
    {
        return $this->model->where('licitacao_id', $licitacaoId)->delete();
    }
}