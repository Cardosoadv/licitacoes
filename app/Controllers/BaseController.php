<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Inicializa o controlador, carrega helpers e executa ações comuns a todos os controladores.
     *
     * @param RequestInterface $request Instância da requisição HTTP
     * @param ResponseInterface $response Instância da resposta HTTP
     * @param LoggerInterface $logger Instância do logger
     *
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Carrega helpers disponíveis em todos os controladores que estendem BaseController.
        $this->helpers = ['url', 'form', 'AppHelper'];

        parent::initController($request, $response, $logger);

        // Disponibiliza informações globais
        $renderer = \Config\Services::renderer();
        $renderer->setVar('app_name', 'PNCP Licitações App');
        $renderer->setVar('app_version', '1.0.0');
    }

    /**
     * Retorna uma resposta de sucesso
     */
    protected function respond($data, $statusCode = 200)
    {
        return $this->response->setJSON($data)->setStatusCode($statusCode);
    }

    /**
     * Retorna uma resposta de erro
     */
    protected function respondError($message, $statusCode = 400)
    {
        return $this->respond(['error' => $message], $statusCode);
    }

    /**
     * Retorna uma resposta de sucesso
     */
    protected function respondSuccess($message, $data = null)
    {
        $response = ['success' => true, 'message' => $message];
        if ($data) {
            $response['data'] = $data;
        }
        return $this->respond($response);
    }

    /**
     * Renderiza uma view
     */
    protected function render($view, $data = [])
    {
        return view($view, $data);
    }
}
