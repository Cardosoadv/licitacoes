<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\SyncService;

class SyncLicitacoes extends BaseCommand
{
    protected $group = 'Licitacoes';
    protected $name = 'licitacoes:sync';
    protected $description = 'Sincroniza licitações do PNCP com o banco de dados local';
    protected $usage = 'licitacoes:sync [dias]';

    public function run(array $params = [])
    {
        $dias = !empty($params[0]) ? (int)$params[0] : 7;

        CLI::write("Iniciando sincronização de licitações (últimos {$dias} dias)...", 'yellow');

        try {
            // Instanciar SyncService (em produção, usar DI Container)
            $syncService = new SyncService(
                new \App\Services\PNCPClientService(),
                new \App\Repositories\LicitacaoRepository(),
                new \App\Repositories\OrgaoRepository(),
                new \App\Services\InsightService(new \App\Repositories\InsightRepository()),
                new \App\Services\NotificacaoService(
                    new \App\Repositories\AlertaRepository(),
                    new \App\Repositories\NotificacaoRepository(),
                    new \App\Repositories\LicitacaoRepository()
                ), 
                new \App\Repositories\SincronizacaoRepository()
            );

            $resultado = $syncService->sincronizarLicitacoes($dias);

            if ($resultado) {
                CLI::write('Sincronização concluída com sucesso!', 'green');
            } else {
                $ultimaSync = $syncService->getUltimaSincronizacao();
                $erro = $ultimaSync['mensagem_erro'] ?? 'Erro desconhecido';
                CLI::write('Erro ao sincronizar licitações: ' . $erro, 'red');
            }
        } catch (\Exception $e) {
            CLI::write("Erro: " . $e->getMessage(), 'red');
        }
    }
}