<?php

namespace App\Services;

use App\Repositories\LicitacaoRepository;
use App\Repositories\OrgaoRepository;
use App\Repositories\SincronizacaoRepository;

class SyncService
{
    protected PNCPClientService $pncpClient;
    protected LicitacaoRepository $licitacaoRepo;
    protected OrgaoRepository $orgaoRepo;
    protected InsightService $insightService;
    protected NotificacaoService $notificacaoService;
    protected SincronizacaoRepository $syncRepo;

    public function __construct(
        PNCPClientService $pncpClient,
        LicitacaoRepository $licitacaoRepo,
        OrgaoRepository $orgaoRepo,
        InsightService $insightService,
        NotificacaoService $notificacaoService,
        SincronizacaoRepository $syncRepo
    ) {
        $this->pncpClient = $pncpClient;
        $this->licitacaoRepo = $licitacaoRepo;
        $this->orgaoRepo = $orgaoRepo;
        $this->insightService = $insightService;
        $this->notificacaoService = $notificacaoService;
        $this->syncRepo = $syncRepo;
    }

    public function sincronizarLicitacoes(int $diasRetroativos = 7): bool
    {
        $syncId = $this->syncRepo->startSync('licitacoes');
 
        try {
            $dataInicio = date('Y-m-d', strtotime("-{$diasRetroativos} days"));
            $dataFim = date('Y-m-d');

            // Modalidades principais: 4 (Concorrência), 5 (Pregão), 6 (Dispensa), 7 (Inexigibilidade)
            $modalidates = [4, 5, 6, 7];
            
            $totalBuscados = 0;
            $totalNovos = 0;
            $totalAtualizados = 0;
            $totalErros = 0;

            foreach ($modalidates as $modalidade) {
                try {
                    $licitacoes = $this->pncpClient->buscarLicitacoesPorPeriodo($dataInicio, $dataFim, $modalidade);
                    $totalBuscados += count($licitacoes);

                    foreach ($licitacoes as $licitacaoData) {
                        try {
                            $this->processarLicitacao($licitacaoData);
                            $totalNovos++;
                        } catch (\Exception $e) {
                            $totalErros++;
                        }
                    }
                } catch (\Exception $e) {
                    // Logar erro da modalidade mas continuar com as outras
                    $this->syncRepo->logError($syncId, "Erro na modalidade {$modalidade}: " . $e->getMessage());
                    $totalErros++;
                }
            }

            $this->syncRepo->endSync($syncId, 'concluido', [
                'total_buscados' => $totalBuscados,
                'total_novos' => $totalNovos,
                'total_atualizados' => $totalAtualizados,
                'total_erros' => $totalErros,
            ]);

            return true;
        } catch (\Exception $e) {
            $this->syncRepo->logError($syncId, $e->getMessage());
            return false;
        }
    }

    public function processarLicitacao(array $licitacaoData): void
    {
        // Mapeamento de campos da API V1 para o modelo interno
        $cnpj = $licitacaoData['orgaoEntidade']['cnpj'] ?? '';
        $ano = $licitacaoData['anoCompra'] ?? date('Y');
        $sequencial = $licitacaoData['numeroSequencial'] ?? '';
        
        $codigoPNCP = "{$cnpj}-{$ano}-{$sequencial}";
        
        $dadosFormatados = [
            'codigo_pncp'        => $codigoPNCP,
            'objeto'             => $licitacaoData['objetoCompra'] ?? $licitacaoData['objeto'] ?? '',
            'modalidade'         => $licitacaoData['modalidadeNome'] ?? '',
            'situacao'           => $licitacaoData['situacaoNome'] ?? '',
            'data_publicacao'    => $licitacaoData['dataPublicacao'] ?? null,
            'data_abertura'      => $licitacaoData['dataAberturaProposta'] ?? null,
            'data_encerramento'  => $licitacaoData['dataEncerramentoProposta'] ?? null,
            'valor_estimado'     => $licitacaoData['valorTotalEstimado'] ?? 0,
            'link_pncp'          => "https://pncp.gov.br/app/editais/{$cnpj}/{$ano}/{$sequencial}",
            'unidade_gestora'    => $licitacaoData['unidadeEntidade']['nomeUnidade'] ?? $licitacaoData['unidadeOrgao']['nomeUnidade'] ?? '',
            'processo'           => $licitacaoData['numeroProcesso'] ?? '',
            'sincronizado_em'    => date('Y-m-d H:i:s'),
        ];

        // Processar órgão
        $orgaoData = [
            'cnpj' => $cnpj,
            'nome' => $licitacaoData['orgaoEntidade']['razaoSocial'] ?? 'Órgão Desconhecido',
        ];
        $orgao = $this->orgaoRepo->findOrCreate($orgaoData);

        // Salvar ou atualizar licitação
        $licitacaoExistente = $this->licitacaoRepo->findByCodigoPNCP($codigoPNCP);
        if ($licitacaoExistente) {
            $this->licitacaoRepo->updateByCodigoPNCP($codigoPNCP, $dadosFormatados);
            $licitacaoId = $licitacaoExistente['id'];
        } else {
            $dadosFormatados['orgao_id'] = $orgao['id'];
            $licitacaoId = $this->licitacaoRepo->create($dadosFormatados);
        }

        // Gerar insight (se houver serviço de LLM configurado)
        try {
            $this->insightService->gerarInsight($codigoPNCP, $dadosFormatados, $orgaoData);
        } catch (\Exception $e) {
            // Logar erro mas não interromper a sincronização
        }

        // Verificar alertas
        $this->notificacaoService->verificarAlertas($licitacaoId);
    }

    public function atualizarStatusLicitacoes()
    {
        // Implementar atualização de status
    }

    public function getUltimaSincronizacao()
    {
        return $this->syncRepo->findLastSync('licitacoes');
    }
}