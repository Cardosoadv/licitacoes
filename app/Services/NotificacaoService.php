<?php

namespace App\Services;

use App\Repositories\AlertaRepository;
use App\Repositories\NotificacaoRepository;
use App\Repositories\LicitacaoRepository;

class NotificacaoService
{
    protected AlertaRepository $alertaRepo;
    protected NotificacaoRepository $notificacaoRepo;
    protected LicitacaoRepository $licitacaoRepo;

    public function __construct(
        AlertaRepository $alertaRepo,
        NotificacaoRepository $notificacaoRepo,
        LicitacaoRepository $licitacaoRepo
    ) {
        $this->alertaRepo = $alertaRepo;
        $this->notificacaoRepo = $notificacaoRepo;
        $this->licitacaoRepo = $licitacaoRepo;
    }

    public function verificarAlertas(int $licitacaoId)
    {
        $licitacao = $this->licitacaoRepo->findById($licitacaoId);
        if (!$licitacao) return;

        $alertas = $this->alertaRepo->findAlertasParaNotificacao($licitacao);

        foreach ($alertas as $alerta) {
            if ($this->alertaCorresponde($alerta, $licitacao, null)) { // orgao pode ser passado
                $this->criarNotificacao($alerta['usuario_id'], $alerta['id'], $licitacaoId);
            }
        }
    }

    public function criarNotificacao(int $usuarioId, int $alertaId, int $licitacaoId): mixed
    {
        $licitacao = $this->licitacaoRepo->findById($licitacaoId);
        $titulo = "Nova licitação: {$licitacao['objeto']}";
        $mensagem = "Uma nova licitação corresponde aos seus critérios de alerta.";

        return $this->notificacaoRepo->create([
            'usuario_id' => $usuarioId,
            'alerta_id' => $alertaId,
            'licitacao_id' => $licitacaoId,
            'titulo' => $titulo,
            'mensagem' => $mensagem,
        ]);
    }

    public function enviarNotificacoesImediatas()
    {
        // Implementar envio imediato
    }

    public function enviarNotificacoesDiarias()
    {
        // Implementar envio diário
    }

    public function enviarNotificacoesSemanais()
    {
        // Implementar envio semanal
    }

    public function alertaCorresponde($alerta, $licitacao, $orgao)
    {
        // Lógica de matching simplificada
        return true; // Sempre corresponde por enquanto
    }
}