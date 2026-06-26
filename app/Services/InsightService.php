<?php

namespace App\Services;

use App\Repositories\InsightRepository;

class InsightService
{
    protected $llmClient;
    protected InsightRepository $insightRepository;

    public function __construct(InsightRepository $insightRepository, $llmClient = null)
    {
        $this->insightRepository = $insightRepository;
        $this->llmClient = $llmClient;
    }

    public function gerarInsight(int $licitacaoId, array $licitacaoData, array $orgaoData): mixed
    {
        $resumo = $this->gerarResumo($licitacaoData['objeto']);
        $palavrasChave = $this->extrairPalavrasChave($licitacaoData['objeto']);
        $setor = $this->classificarSetor($licitacaoData['objeto']);
        $score = $this->calcularScoreOportunidade($licitacaoData, $orgaoData);

        $insight = [
            'resumo' => $resumo,
            'palavras_chave' => json_encode($palavrasChave),
            'setor' => $setor,
            'oportunidade_score' => $score,
            'analise_completa' => json_encode([]), // Placeholder
        ];

        return $this->insightRepository->saveOrUpdate($licitacaoId, $insight);
    }

    public function gerarResumo(string $objeto): string
    {
        // Simulação de LLM - em produção, chamar API real
        return substr($objeto, 0, 200) . '...';
    }

    public function extrairPalavrasChave(string $texto): array
    {
        // Simulação simples
        return ['licitação', 'contrato', 'serviços'];
    }

    public function classificarSetor(string $objeto): string
    {
        // Simulação
        return 'Serviços Gerais';
    }

    public function calcularScoreOportunidade(array $licitacaoData, array $orgaoData): int
    {
        // Lógica simples baseada em valor
        $valor = $licitacaoData['valor_estimado'] ?? 0;
        if ($valor > 1000000) return 10;
        if ($valor > 500000) return 8;
        if ($valor > 100000) return 6;
        return 4;
    }

    public function validarInsight(array $insight): bool
    {
        return !empty($insight['resumo']) && isset($insight['oportunidade_score']);
    }
}