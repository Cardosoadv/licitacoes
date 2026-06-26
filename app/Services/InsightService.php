<?php

namespace App\Services;

use App\Repositories\InsightRepository;

class InsightService
{
    protected $llmClient;
    protected $insightRepository;

    public function __construct(InsightRepository $insightRepository, $llmClient = null)
    {
        $this->insightRepository = $insightRepository;
        $this->llmClient = $llmClient;
    }

    public function gerarInsight($licitacaoId, $licitacaoData, $orgaoData)
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

    public function gerarResumo($objeto)
    {
        // Simulação de LLM - em produção, chamar API real
        return substr($objeto, 0, 200) . '...';
    }

    public function extrairPalavrasChave($texto)
    {
        // Simulação simples
        return ['licitação', 'contrato', 'serviços'];
    }

    public function classificarSetor($objeto)
    {
        // Simulação
        return 'Serviços Gerais';
    }

    public function calcularScoreOportunidade($licitacaoData, $orgaoData)
    {
        // Lógica simples baseada em valor
        $valor = $licitacaoData['valor_estimado'] ?? 0;
        if ($valor > 1000000) return 10;
        if ($valor > 500000) return 8;
        if ($valor > 100000) return 6;
        return 4;
    }

    public function validarInsight($insight)
    {
        return !empty($insight['resumo']) && isset($insight['oportunidade_score']);
    }
}