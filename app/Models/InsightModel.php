<?php
namespace App\Models;

use CodeIgniter\Model;

class InsightModel extends Model
{
    protected $table = 'insights_licitacoes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'licitacao_id', 'resumo', 'palavras_chave', 'setor', 
        'oportunidade_score', 'analise_completa'
    ];
    protected $useTimestamps = true;
}
