<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


class PNCPClientService
{
    protected $httpClient;
    protected $config;

    public function __construct($httpClient = null, $config = [])
    {
        $this->httpClient = $httpClient ?? new Client([
            'base_uri' => getenv('PNCP_API_URL') ?: 'https://pncp.gov.br/api/consulta/',
            'timeout' => 60,
        ]);
        $this->config = $config;
    }

    public function buscarLicitacoes($params = [])
    {
        return $this->requestWithRetry('v1/contratacoes/publicacao', $params);
    }

    public function buscarLicitacaoPorCodigo($cnpj, $ano, $sequencial)
    {
        return $this->requestWithRetry("orgaos/{$cnpj}/licitacoes/{$ano}/{$sequencial}");
    }

    public function buscarOrgaoPorCNPJ($cnpj)
    {
        return $this->requestWithRetry("orgaos/{$cnpj}");
    }

    public function buscarLicitacoesPorPeriodo($dataInicio, $dataFim, $modalidade, $pagina = 1)
    {
        return $this->buscarLicitacoes([
            'dataInicial' => date('Ymd', strtotime($dataInicio)),
            'dataFinal'   => date('Ymd', strtotime($dataFim)),
            'codigoModalidadeContratacao' => $modalidade,
            'pagina'      => $pagina
        ]);
    }

    public function requestWithRetry($endpoint, $params = [], $maxRetries = 3)
    {
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

                $response = $this->httpClient->get($endpoint, [
                    'query' => $params,
                    'headers' => $headers,
                ]);

                $data = json_decode($response->getBody()->getContents(), true);
                
                // Se for consulta, os resultados estão em 'data'
                return $data['data'] ?? $data;
            } catch (RequestException $e) {
                $retries++;
                if ($retries >= $maxRetries) {
                    throw $e;
                }
                sleep(1); // Wait before retry
            }
        }
    }
}