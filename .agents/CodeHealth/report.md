# Code Health Report - 10/04/2026

## Refatoração de Construtor
- **Problema:** `InsightService` possuía um parâmetro opcional antes de um parâmetro obrigatório, o que causava confusão e erros de contagem de argumentos em instanciacões manuais.
- **Ação:** Refatorado o construtor do `InsightService` para que `InsightRepository` seja o primeiro argumento.
- **Impacto:** Melhoria na legibilidade e robustez do código. Eliminado o erro `ArgumentCountError` em todo o projeto.

# Code Health Report - 26/06/2026

## Refatoração de Controllers (Alerta e Notificacao)
- **Problema:** Lógica de autenticação (`$this->authService->requireAuth()`) e de validação de entidade (`$alerta['usuario_id'] != $user->id`) estava altamente duplicada, infringindo o princípio DRY. O método `AlertaController::criar` estava sem o guard de autenticação, o que poderia gerar erros em tempo de execução.
- **Ação:** Criados métodos protegidos `requireUser()`, `findAlertaOrFail($id, $userId)` e `findNotificacaoOrFail($id, $userId)` para extrair a repetição de código em `AlertaController` e `NotificacaoController`.
- **Impacto:** Código significativamente mais limpo e legível. Eliminação do bug de acesso desprotegido em `criar`. Manutenção simplificada para futuras validações de autorização.
