# PNCP Licitações App - Documentação

## Visão Geral

Sistema de monitoramento, análise e gerenciamento de licitações públicas brasileiras construído com **CodeIgniter 4** seguindo a arquitetura **MVC+R+S** (Model-View-Controller + Repository + Service).

## Características Principais

- ✅ **Sincronização de Licitações**: Integração com API do PNCP
- ✅ **Análise Inteligente**: Insights com LLM (OpenAI, etc)
- ✅ **Alertas Personalizados**: Sistema de notificações por critérios
- ✅ **Dashboard Intuitivo**: Visualização de estatísticas e tendências
- ✅ **API REST**: Endpoints para integração externa
- ✅ **Autenticação OAuth**: Integração com servidor Manus

## Arquitetura

```
app/
├── Config/           # Configurações do framework
├── Controllers/      # Controladores (HTTPs handlers)
├── Models/          # Modelos (Active Record)
├── Repositories/    # Data Access Objects
├── Services/        # Lógica de negócio
├── Commands/        # Comandos CLI
├── Database/
│   ├── Migrations/  # Schema do banco
│   └── Seeds/       # Dados iniciais
└── Views/           # Templates HTML
```

## Começando

### Pré-requisitos

- PHP >= 8.1
- MySQL 8.0+
- Composer
- Node.js (opcional, para frontend assets)

### Instalação

1. **Clonar o repositório**
   ```bash
   git clone <repo-url>
   cd licitacoes
   ```

2. **Instalar dependências PHP**
   ```bash
   composer install
   ```

3. **Configurar ambiente**
   ```bash
   cp .env.example .env
   # Editar .env com suas credenciais
   ```

4. **Criar tabelas do banco**
   ```bash
   php spark migrate
   ```

5. **Popular dados iniciais** (opcional)
   ```bash
   php spark db:seed OrgaosSeeder
   ```

6. **Iniciar servidor**
   ```bash
   php spark serve
   ```

## Configuração

### Variáveis de Ambiente (.env)

```bash
# Banco de dados
DATABASE_HOSTNAME=localhost
DATABASE_DATABASE=pncp_licitacoes
DATABASE_USERNAME=usuario
DATABASE_PASSWORD=senha

# API PNCP
PNCP_API_URL=https://pncp.gov.br/api
PNCP_API_TOKEN=seu_token

# LLM (OpenAI, etc)
LLM_API_URL=https://api.openai.com/v1
LLM_API_KEY=sua_chave
LLM_MODEL=gpt-3.5-turbo

# OAuth Manus
OAUTH_CLIENT_ID=seu_client_id
OAUTH_CLIENT_SECRET=seu_secret
OAUTH_REDIRECT_URI=http://localhost/licitacoes/auth/callback
```

## Banco de Dados

### Tabelas Principais

- **users**: Usuários do sistema
- **orgaos**: Órgãos públicos
- **licitacoes**: Licitações públicas
- **insights_licitacoes**: Análises inteligentes
- **alertas_usuarios**: Alertas personalizados
- **notificacoes**: Centro de notificações
- **sincronizacoes**: Log de sincronizações

## Endpoints da API

### Licitações
- `GET /api/licitacoes` - Listar licitações
- `GET /api/licitacoes/:id` - Detalhes da licitação
- `GET /api/estatisticas` - Estatísticas gerais

### Alertas
- `GET /api/alertas` - Listar alertas do usuário
- `POST /api/alertas` - Criar alerta

### Notificações
- `GET /api/notificacoes` - Listar notificações

## Comandos CLI

```bash
# Sincronizar licitações (últimos 7 dias)
php spark licitacoes:sync

# Sincronizar últimos 30 dias
php spark licitacoes:sync 30

# Gerar insights para licitações
php spark licitacoes:insights

# Enviar notificações pendentes
php spark notificacoes:enviar
```

## Estrutura de Repositórios

Os repositórios seguem o padrão **Data Mapper** para abstração do banco:

```php
// Exemplo de uso
$licitacaoRepo = new LicitacaoRepository();

// Método básicos (BaseRepository)
$all = $licitacaoRepo->findAll();
$byId = $licitacaoRepo->findById(1);

// Métodos específicos
$byFilters = $licitacaoRepo->findByFilters($filters);
$stats = $licitacaoRepo->getEstatisticasGerais();
```

## Estrutura de Services

Services implementam a lógica de negócio e coordenam múltiplos repositórios:

```php
// Exemplo
$syncService = new SyncService(
    $pncpClient,
    $licitacaoRepo,
    $orgaoRepo,
    $insightService,
    $notificacaoService,
    $syncRepo
);

$syncService->sincronizarLicitacoes(7); // Sincronizar últimos 7 dias
```

## Views e Templates

### Estrutura de Views

```
views/
├── home/
│   ├── dashboard.php
│   └── estatisticas.php
├── licitacoes/
│   ├── index.php
│   ├── listar.php
│   ├── detalhes.php
│   └── filtros.php
├── alertas/
│   ├── index.php
│   └── form.php
├── auth/
│   ├── login.php
│   └── perfil.php
└── notificacoes/
    └── index.php
```

## Testes

```bash
# Executar testes unitários
vendor/bin/phpunit

# Executar testes com cobertura
vendor/bin/phpunit --coverage-html coverage/
```

## Deploy

### Docker

```bash
# Build
docker-compose build

# Run
docker-compose up -d

# Migrations
docker-compose exec app php spark migrate
```

### NGINX

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Troubleshooting

### Erro: "Database connection error"
- Verificar credenciais no `.env`
- Garantir que o MySQL está rodando
- Verificar permissões de banco

### Erro: "CSRF token mismatch"
- Limpar cookies do navegador
- Verificar configuração de CSRF em `Config/Security.php`

### Erro de Migrations
- Deletar arquivo `writable/migrations.json`
- Rodar `php spark migrate:refresh` (cuidado em produção!)

## Contribuindo

1. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
2. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
3. Push para a branch (`git push origin feature/AmazingFeature`)
4. Abra um Pull Request

## Licença

Este projeto é licenciado sob a MIT License - veja o arquivo [LICENSE](LICENSE) para detalhes.

## Suporte

Para suporte, envie um email para support@example.com ou abra uma issue no repositório.

## Changelog

### v1.0.0 (2026-04-07)
- Release inicial
- Sistema completo de licitações
- API REST
- Dashboard com estatísticas
- Sistema de alertas e notificações