<?php
namespace App\Models;

use CodeIgniter\Model;

class SincronizacaoModel extends Model
{
    protected $table = 'sincronizacoes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tipo', 'data_inicio', 'data_fim', 'status', 
        'total_buscados', 'total_novos', 'total_atualizados', 
        'total_erros', 'mensagem_erro', 'detalhes'
    ];
    protected $useTimestamps = false; // Manually handled in migration/repo
}
