<?php
namespace App\Models;

use CodeIgniter\Model;

class NotificacaoModel extends Model
{
    protected $table = 'notificacoes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'usuario_id', 'alerta_id', 'licitacao_id', 'titulo', 
        'mensagem', 'lida', 'enviada_em'
    ];
    protected $useTimestamps = true;
    protected $updatedField  = ''; // Not using updated_at in this table
}
