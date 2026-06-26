<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\InsightService;
use App\Repositories\LicitacaoRepository;
use App\Repositories\InsightRepository;

class GerarInsights extends BaseCommand
{
    protected $group = 'Licitacoes';
    protected $name = 'licitacoes:insights';
    protected $description = 'Gera insights para licitações sem análise';
    protected $usage = 'licitacoes:insights [limit]';

    public function run(array $params = [])
    {
        $limit = !empty($params[0]) ? (int)$params[0] : 50;

        CLI::write("Gerando insights para licitações (limite: {$limit})...", 'yellow');

        try {
            $licitacaoRepo = new LicitacaoRepository();
            $insightRepo = new InsightRepository();
            $insightService = new InsightService($insightRepo);

            // Buscar licitações sem insights
            $licitacoes = $licitacaoRepo->model->where('id NOT IN (SELECT licitacao_id FROM insights_licitacoes)', null, false)
                                              ->findAll($limit);

            foreach ($licitacoes as $licitacao) {
                try {
                    $insightService->gerarInsight($licitacao['id'], $licitacao, []);
                    CLI::write("Insight gerado para licitação #{$licitacao['id']}", 'green');
                } catch (\Exception $e) {
                    CLI::write("Erro ao gerar insight para licitação #{$licitacao['id']}: " . $e->getMessage(), 'red');
                }
            }

            CLI::write('Geração de insights concluída!', 'green');
        } catch (\Exception $e) {
            CLI::write("Erro: " . $e->getMessage(), 'red');
        }
    }
}