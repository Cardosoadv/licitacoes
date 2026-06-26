<?php

namespace App\Controllers;

use App\Repositories\AlertaRepository;
use App\Services\AuthService;

class AlertaController extends BaseController
{
    protected AlertaRepository $alertaRepo; //TODO: Substituir pelo Service. Sem chamada direta a repositorio no controller. (codigo não limpo)
    protected AuthService $authService;

    public function __construct(?AlertaRepository $alertaRepo=null, ?AuthService $authService=null)
    {
        $this->alertaRepo = $alertaRepo ?? new AlertaRepository();
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
        $alerta = $this->alertaRepo->findById($id);

        if (!$alerta || $alerta['usuario_id'] != $userId) {
            return null;
        }

        return $alerta;
    }

    public function index()
    {
        $user = $this->requireUser();
        $alertas = $this->alertaRepo->findByUsuario($user->id);

        return $this->render('alertas/index', ['alertas' => $alertas]);
    }

    public function listar()
    {
        $user = $this->requireUser();
        $alertas = $this->alertaRepo->findByUsuario($user->id);

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

        $id = $this->alertaRepo->create($data);

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
        $this->alertaRepo->update($id, $data);

        return $this->respondSuccess('Alerta atualizado');
    }

    public function delete($id)
    {
        $user = $this->requireUser();
        $alerta = $this->findAlertaOrFail($id, $user->id);

        if (!$alerta) {
            return $this->respondError('Alerta não encontrado', 404);
        }

        $this->alertaRepo->delete($id);

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
        $this->alertaRepo->update($id, ['ativo' => $ativo]);

        return $this->respondSuccess('Alerta ' . ($ativo ? 'ativado' : 'desativado'));
    }
}