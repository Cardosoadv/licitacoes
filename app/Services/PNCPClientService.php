<?php

namespace App\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class PNCPClientService
{
    protected Client $httpClient;
    protected array $config;

    public function __construct(?Client $httpClient = null, array $config = [])
    {
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => getenv('PNCP_API_URL') ?: 'https://pncp.gov.br/api/consulta/',
            'timeout' => 60,
        ]);
        $this->config = $config;
    }

    public function buscarLicitacoes(array $params = []): array
    {
        return $this->requestWithRetry('v1/contratacoes/publicacao', $params);
    }

    public function buscarLicitacaoPorCodigo(string $cnpj, string $ano, string $sequencial): array
    {
        return $this->requestWithRetry("orgaos/{$cnpj}/licitacoes/{$ano}/{$sequencial}");
    }

    public function buscarOrgaoPorCNPJ(string $cnpj): array
    {
        return $this->requestWithRetry("orgaos/{$cnpj}");
    }

    public function buscarLicitacoesPorPeriodo(string $dataInicio, string $dataFim, string $modalidade, int $pagina = 1): array
    {
        return $this->buscarLicitacoes([
            'dataInicial' => date('Ymd', strtotime($dataInicio)),
            'dataFinal'   => date('Ymd', strtotime($dataFim)),
            'codigoModalidadeContratacao' => $modalidade,
            'pagina'      => $pagina
        ]);
    }

    public function requestWithRetry(string $endpoint, array $params = [], int $maxRetries = 3): array
    {
        if ($maxRetries <= 0) {
            throw new \InvalidArgumentException("maxRetries must be greater than 0");
        }

        $retries = 0;
        while ($retries < $maxRetries) {
            try {
                $headers = [
                    'Accept' => 'application/json',
                ];

                $token = getenv('PNCP_API_TOKEN');
                if (!empty($token)) {
                    $headers['Authorization'] = 'Bearer ' . $token; 
                }

                log_message('info', "PNCP Request Endpoint: {$endpoint}, Params: " . json_encode($params));

                $response = $this->httpClient->get($endpoint, [
                    'query' => $params,
                    'headers' => $headers,
                ]);

                $responseBody = $response->getBody()->getContents();
                log_message('info', "PNCP Response Body: " . substr($responseBody, 0, 1000) . (strlen($responseBody) > 1000 ? "..." : ""));

                $data = json_decode($responseBody, true);
                
                // Se for consulta, os resultados estão em 'data'
                $result = $data['data'] ?? $data;
                log_message('info', "PNCP Request decoded result count: " . (is_array($result) ? count($result) : 'not array'));
                
                return $result;
            } catch (RequestException $e) {
                log_message('error', "PNCP Request Error: " . $e->getMessage());
                if ($e->hasResponse()) {
                    log_message('error', "PNCP Response Error Body: " . $e->getResponse()->getBody()->getContents());
                }
                
                $retries++;
                if ($retries >= $maxRetries) {
                    throw $e;
                }
                sleep(1); // Wait before retry
            }
        }
        
        throw new \RuntimeException("Failed to complete request to $endpoint");
    }
}