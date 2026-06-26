<?php
namespace App\Models;

use CodeIgniter\Model;

class AlertaModel extends Model
{
    protected $table = 'alertas_usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'usuario_id', 'nome', 'palavras_chave', 'modalidades', 
        'valor_minimo', 'valor_maximo', 'situacoes', 'ufs', 
        'frequencia', 'ativo', 'ultima_notificacao'
    ];
    protected $useTimestamps = true;
}
