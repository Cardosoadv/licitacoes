# Relatório de Evolução - 10/04/2026

## Correções Realizadas
- **Erro de Argumentos (InsightService):** Corrigido o erro `ArgumentCountError` causado pela instanciação do `InsightService` sem os argumentos necessários.
- **Refatoração de Construtor:** Invertida a ordem dos argumentos no construtor do `InsightService` para que `InsightRepository` (obrigatório) venha antes de `$llmClient` (opcional).
- **Atualização de Controladores e Comandos:**
    - `LicitacaoController`: Atualizado para instanciar e passar o `InsightRepository`.
    - `GerarInsights` (CLI): Atualizado para instanciar e passar o `InsightRepository`.
    - `SyncLicitacoes` (CLI): Atualizado para instanciar e passar o `InsightRepository`.
- **Correção de View de Modal:** Criado o arquivo `app/Views/componentes/modal_alterar_senha.php` que estava ausente, resolvendo o `ViewException`.
- **Configuração de Rota:** Adicionada a rota `/api/perfil/alterar-senha` no `Routes.php` para processar a alteração de senha via AJAX.
- **Robustez no Perfil:** Carregado explicitamente o helper `auth` no controller `Perfil` para garantir o funcionamento do Shield.

# Relatório de Evolução - 12/04/2026

## Correções Realizadas
- **Erro de Acesso a Entidade (User Entity):** Corrigido o erro `Cannot use object of type CodeIgniter\Shield\Entities\User as array`. O objeto de usuário do Shield agora é acessado corretamente via propriedade (`$user->id`) em vez de sintaxe de array (`$user['id']`).
- **Arquivos Atualizados:**
    - `app/Services/AuthService.php`: Corrigido o método `isLoggedIn`.
    - `app/Controllers/AuthController.php`: Corrigido o método `perfil`.
    - `app/Controllers/AlertaController.php`: Corrigidos múltiplos acessos ao ID do usuário.
    - `app/Controllers/NotificacaoController.php`: Corrigidos múltiplos acessos ao ID do usuário.
    - `app/Controllers/ApiController.php`: Corrigidos múltiplos acessos ao ID do usuário.
- **Criação de Views Ausentes:** Resolvido o `ViewException` ao criar as views que estavam faltando no sistema, seguindo o padrão visual premium.
- **Novas Views:**
    - `app/Views/alertas/index.php`: Gerenciamento de alertas.
    - `app/Views/alertas/form.php`: Formulário de criação/edição.
    - `app/Views/notificacoes/index.php`: Central de notificações.

## Limpeza e Rebrand Visual
- **Remoção de Referências a Pets:** Removidas todas as menções a "Petys", "Casa dos Pets" e outros termos veterinários das views.
- **Substituição de Ícones e Emojis:** Todos os emojis de pata (`🐾`) e cachorro (`🐶`) foram substituídos por ícones institucionais e de licitação (`🏛️`, `📊`, `🛠️`).
- **Adequação de Contexto:** 
    - Dashboard atualizado com KPIs de "Licitações" e "Processos".
    - Menu lateral renomeado para "Licitações", "Dossiês" e "Importar Dados".
    - Classes CSS semânticas renomeadas (ex: `patient-cell` -> `item-cell`).
    - Placeholders de e-mail atualizados para `@licitacoes.com.br`.
- **Arquivos Impactados:** `layout.php`, `sidebar.php`, `index.php`, `listar.php`, `detalhes.php` e views do Shield.

# Relatório de Evolução - 17/04/2026

## Integração com PNCP (API V1)
- **Revisão da API PNCP:** O `PNCPClientService` foi atualizado para utilizar a API oficial V1 (`https://pncp.gov.br/api/pncp/v1`).
- **Endpoint de Consulta:** Alterado o acionamento de `/licitacoes` para `/consulta`, permitindo filtragem por período e paginação.
- **Formatação de Datas:** Implementada a conversão automática de datas para o formato `YYYYMMDD` exigido pelo PNCP.
- **Mapeamento de Dados:** O `SyncService` foi ajustado para mapear corretamente os campos da API V1 (ex: `objeto`, `valorTotalEstimado`, `dataPublicacao`) para o banco de dados local.
- **Construção de Links:** Implementada a geração dinâmica de links diretos para o edital no portal PNCP.

## Interface e Dashboard
- **Novo Card de Acesso:** Adicionado card dedicado ao PNCP no dashboard principal (`home/dashboard.php`).
- **Estética Premium:** Aplicado estilo visual moderno aos cards do dashboard, utilizando gradientes, sombras e ícones do Bootstrap Icons.
- **Melhoria de UX:** Cards agora possuem layout responsivo e botões de ação rápida com design arredondado.

## Arquivos Atualizados
- `app/Services/PNCPClientService.php`
- `app/Services/SyncService.php`
- `app/Views/home/dashboard.php`
- `app/Commands/SyncLicitacoes.php`
- `.env`
- `.agents/relatorio_evolucao.md`

## Correção Crítica (Sincronização)
- **Ajuste de Endpoints:** Corrigida a URL base da API de Consulta para `https://pncp.gov.br/api/consulta/` e o endpoint para `v1/contratacoes/publicacao`.
- **Resiliência por Modalidade:** Implementado loop que itera pelas modalidades 4, 5, 6 e 7, com tratamento de erros individualizado para evitar que falhas em uma modalidade interrompam a sincronização global.
- **Timeout e Performance:** Aumentado o timeout da API para 60 segundos para evitar erros de resposta lenta do portal PNCP.
- **Mapeamento V1:** Atualizado o mapeamento de campos para suportar as chaves da API V1 (ex: `objetoCompra`, `unidadeEntidade`).
- **Feedback no CLI:** O comando `licitacoes:sync` agora exibe detalhadamente o erro ocorrido caso a sincronização falhe.

# Relatório de Evolução - 26/06/2026

## Code Health e Refatoração (v1.0.1)
- **Refatoração de Controllers:** Lógica de autenticação duplicada (`$this->authService->requireAuth()`) foi extraída para o método `requireUser()` dentro de `AlertaController` e `NotificacaoController`.
- **Validação de Autorização:** Criados métodos auxiliares `findAlertaOrFail` e `findNotificacaoOrFail` para reaproveitar a validação de acesso às entidades vinculadas ao usuário logado, respeitando o princípio DRY (Don't Repeat Yourself).
- **Correção de Bug de Segurança:** Adicionada validação de autenticação ausente no método `AlertaController::criar()`.
- **Atualização de Versão:** Versão do sistema em `README.md` e `composer.json` atualizada para a `v1.0.1`.

## Code Health e Refatoração (v1.0.2)
- **Criação de Serviços:** Criados os serviços `AlertaService`, `LicitacaoService` e `OrgaoService` para encapsular a lógica de acesso a dados.
- **Ampliação de Serviços:** Adicionados os métodos que faltavam em `NotificacaoService` para delegar chamadas ao `NotificacaoRepository`.
- **Limpeza de Controllers:** Resolvidos os "TODOs" (código não limpo) removendo o acesso direto a repositórios nos controllers.
- **Atualização de Dependências:** `AlertaController`, `LicitacaoController`, `ApiController` e `NotificacaoController` passaram a injetar e utilizar Serviços em vez de Repositórios.
- **Atualização de Versão:** Versão do sistema atualizada para `v1.0.2` em `README.md` e `composer.json`.
