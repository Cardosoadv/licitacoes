<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Perfil extends BaseController
{
    /**
     * Altera a senha do usuário logado
     */
    public function alterarSenha()
    {
        helper('auth');
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['status' => 'error', 'message' => 'Acesso negado']);
        }

        $rules = [
            'current_password'     => 'required',
            'new_password'         => 'required|min_length[8]',
            'new_password_confirm' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Por favor, corrija os erros: ' . implode(', ', $this->validator->getErrors())
            ]);
        }

        $user               = auth()->user();
        $currentPassword    = $this->request->getPost('current_password');
        $newPassword        = $this->request->getPost('new_password');

        // Validar senha atual
        $credentials = [
            'email'    => $user->email,
            'password' => $currentPassword,
        ];

        $result = auth()->check($credentials);

        if (!$result->isOK()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'A senha atual informada está incorreta.'
            ]);
        }

        // Atualizar senha
        $user->password = $newPassword;
        $users = auth()->getProvider();

        if ($users->save($user)) {
            return $this->response->setJSON([
                'status'  => 'success',
                'message' => 'Sua senha foi alterada com sucesso!'
            ]);
        }

        return $this->response->setJSON([
            'status'  => 'error',
            'message' => 'Ocorreu um erro ao tentar salvar a nova senha.'
        ]);
    }
}
