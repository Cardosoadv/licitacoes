<?php

namespace App\Controllers;

use App\Services\AlertaService;
use App\Services\AuthService;

class AlertaController extends BaseController
{
    protected AlertaService $alertaService;
    protected AuthService $authService;

    public function __construct(?AlertaService $alertaService=null, ?AuthService $authService=null)
    {
        $this->alertaService = $alertaService ?? new AlertaService();
        $this->authService = $authService ?? new AuthService(session());
    }

    /**
     * Garante que o usuário esteja autenticado e o retorna.
     */
    protected function requireUser()
    {
        $this->authService->requireAuth();
        return $this->authService->getAuthenticatedUser();
    }

    /**
     * Busca o alerta e verifica se pertence ao usuário.
     */
    protected function findAlertaOrFail($id, $userId)
    {
        $alerta = $this->alertaService->findById($id);

        if (!$alerta || $alerta['usuario_id'] != $userId) {
            return null;
        }

        return $alerta;
    }

    public function index()
    {
        $user = $this->requireUser();
        $alertas = $this->alertaService->findByUsuario($user->id);

        return $this->render('alertas/index', ['alertas' => $alertas]);
    }

    public function listar()
    {
        $user = $this->requireUser();
        $alertas = $this->alertaService->findByUsuario($user->id);

        return $this->respondSuccess('Alertas listados', $alertas);
    }

    public function criar()
    {
        $this->requireUser(); // Garante a autenticação mesmo ao apenas renderizar o formulário
        return $this->render('alertas/form');
    }

    public function store()
    {
        $user = $this->requireUser();
        $data = $this->request->getJSON(true);
        $data['usuario_id'] = $user->id;

        $id = $this->alertaService->create($data);

        return $this->respondSuccess('Alerta criado', ['id' => $id]);
    }

    public function editar($id)
    {
        $user = $this->requireUser();
        $alerta = $this->findAlertaOrFail($id, $user->id);

        if (!$alerta) {
            return $this->respondError('Alerta não encontrado', 404);
        }

        return $this->render('alertas/form', ['alerta' => $alerta]);
    }

    public function update($id)
    {
        $user = $this->requireUser();
        $alerta = $this->findAlertaOrFail($id, $user->id);

        if (!$alerta) {
            return $this->respondError('Alerta não encontrado', 404);
        }

        $data = $this->request->getJSON(true);
        $this->alertaService->update($id, $data);

        return $this->respondSuccess('Alerta atualizado');
    }

    public function delete($id)
    {
        $user = $this->requireUser();
        $alerta = $this->findAlertaOrFail($id, $user->id);

        if (!$alerta) {
            return $this->respondError('Alerta não encontrado', 404);
        }

        $this->alertaService->delete($id);

        return $this->respondSuccess('Alerta deletado');
    }

    public function toggle($id)
    {
        $user = $this->requireUser();
        $alerta = $this->findAlertaOrFail($id, $user->id);

        if (!$alerta) {
            return $this->respondError('Alerta não encontrado', 404);
        }

        $ativo = !$alerta['ativo'];
        $this->alertaService->update($id, ['ativo' => $ativo]);

        return $this->respondSuccess('Alerta ' . ($ativo ? 'ativado' : 'desativado'));
    }
}