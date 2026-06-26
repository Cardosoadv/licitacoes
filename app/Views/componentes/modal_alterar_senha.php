<!-- Modal Alterar Senha -->
<div id="modal-alterar-senha" class="modal-overlay hidden">
    <div class="modal-content modal-content--small">
        <div class="modal-header">
            <h3><span class="icon">🔐</span> Alterar Minha Senha</h3>
            <button class="close-modal" onclick="closeModal('alterar-senha')">&times;</button>
        </div>
        <form id="form-alterar-senha" onsubmit="submitAlterarSenha(event)">
            <div class="modal-body">
                <p class="modal-description">Escolha uma senha forte para manter sua conta segura.</p>
                
                <div id="msg-erro-senha" class="alert alert-danger mb-3 hide"></div>

                <div class="form-group mb-3">
                    <label for="current_password">Senha Atual</label>
                    <div class="input-with-icon">
                        <span class="input-icon">🔑</span>
                        <input type="password" id="current_password" name="current_password" class="form-control" required placeholder="Digite sua senha atual">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="new_password">Nova Senha</label>
                    <div class="input-with-icon">
                        <span class="input-icon">🛡️</span>
                        <input type="password" id="new_password" name="new_password" class="form-control" required placeholder="Mínimo 8 caracteres">
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="new_password_confirm">Confirmar Nova Senha</label>
                    <div class="input-with-icon">
                        <span class="input-icon">🛡️</span>
                        <input type="password" id="new_password_confirm" name="new_password_confirm" class="form-control" required placeholder="Repita a nova senha">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('alterar-senha')">Cancelar</button>
                <button type="submit" id="btn-save-senha" class="btn btn-primary">
                    <span class="btn-text">Salvar Nova Senha</span>
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    opacity: 1;
    transition: all 0.3s ease;
}

.modal-overlay.hidden {
    display: none;
    opacity: 0;
}

.modal-content--small {
    max-width: 450px;
    width: 90%;
    background: var(--bg-card, #fff);
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    overflow: hidden;
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark, #333);
}

.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    color: #999;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.modal-body {
    padding: 24px;
}

.modal-description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    color: #444;
}

.input-with-icon {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 1rem;
    opacity: 0.7;
}

.input-with-icon .form-control {
    padding-left: 40px;
    height: 48px;
    border-radius: 10px;
    border: 1.5px solid #eee;
    transition: all 0.2s;
}

.input-with-icon .form-control:focus {
    border-color: var(--primary-color, #4a90e2);
    box-shadow: 0 0 0 4px rgba(74, 144, 226, 0.1);
}

.modal-footer {
    padding: 16px 24px;
    background: #f9f9f9;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

.btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-primary {
    background: var(--primary-color, #4a90e2);
    color: #fff;
}

.btn-secondary {
    background: #eee;
    color: #666;
}

.btn:hover {
    filter: brightness(0.9);
}

.alert {
    padding: 12px;
    border-radius: 8px;
    font-size: 0.85rem;
}

.alert-danger {
    background: #ffe5e5;
    color: #d63031;
    border-left: 4px solid #d63031;
}

.hide { display: none !important; }
</style>
