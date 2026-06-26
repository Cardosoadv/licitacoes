<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\NotificacaoService;

class EnviarNotificacoes extends BaseCommand
{
    protected $group = 'Notificacoes';
    protected $name = 'notificacoes:enviar';
    protected $description = 'Envia notificações pendentes aos usuários';
    protected $usage = 'notificacoes:enviar';

    public function run(array $params = [])
    {
        CLI::write("Enviando notificações...", 'yellow');

        try {
            $notificacaoService = new NotificacaoService(
                new \App\Repositories\AlertaRepository(),
                new \App\Repositories\NotificacaoRepository(),
                new \App\Repositories\LicitacaoRepository()
            );

            $notificacaoService->enviarNotificacoesImediatas();
            $notificacaoService->enviarNotificacoesDiarias();
            $notificacaoService->enviarNotificacoesSemanais();

            CLI::write('Notificações enviadas com sucesso!', 'green');
        } catch (\Exception $e) {
            CLI::write("Erro: " . $e->getMessage(), 'red');
        }
    }
}