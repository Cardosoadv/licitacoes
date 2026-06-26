<?php

namespace App\Models;

use CodeIgniter\Model;

class LicitacaoModel extends Model
{
    protected $table = 'licitacoes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'codigo_pncp', 'orgao_id', 'objeto', 'modalidade', 'situacao',
        'data_publicacao', 'data_abertura', 'data_encerramento',
        'valor_estimado', 'link_pncp', 'unidade_gestora', 'processo', 'sincronizado_em'
    ];
    protected $useTimestamps = true;
    protected $validationRules = [
        'codigo_pncp' => 'required',
        'orgao_id' => 'required|integer',
        'objeto' => 'required',
        'modalidade' => 'required',
        'situacao' => 'required',
    ];

    public function withOrgao()
    {
        return $this->join('orgaos', 'orgaos.id = licitacoes.orgao_id');
    }

    public function withInsight()
    {
        return $this->join('insights_licitacoes', 'insights_licitacoes.licitacao_id = licitacoes.id', 'left');
    }

    public function search($termo)
    {
        return $this->like('objeto', $termo);
    }

    public function filterByModalidade($modalidades)
    {
        if (!empty($modalidades)) {
            return $this->whereIn('modalidade', $modalidades);
        }
        return $this;
    }

    public function filterBySituacao($situacoes)
    {
        if (!empty($situacoes)) {
            return $this->whereIn('situacao', $situacoes);
        }
        return $this;
    }

    public function filterByPeriodo($dataInicio, $dataFim)
    {
        if ($dataInicio) {
            $this->where('data_publicacao >=', $dataInicio);
        }
        if ($dataFim) {
            $this->where('data_publicacao <=', $dataFim);
        }
        return $this;
    }

    public function filterByValor($min, $max)
    {
        if ($min !== null) {
            $this->where('valor_estimado >=', $min);
        }
        if ($max !== null) {
            $this->where('valor_estimado <=', $max);
        }
        return $this;
    }
}