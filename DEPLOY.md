# Deploy & DevOps Guide - PNCP Licitações

## 🐳 Docker Setup

### Build e Run

```bash
# Build da imagem
docker-compose build

# Iniciar containers
docker-compose up -d

# Parar containers
docker-compose down

# Ver logs
docker-compose logs -f app
```

### Serviços Disponíveis

- **App**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081 (user: pncp_user / pass: secure_password)
- **Redis**: localhost:6379 (para cache)

## 🔧 Configuração em Produção

### 1. NGINX Configuration

```nginx
upstream php {
    server php:9000;
}

server {
    listen 80;
    server_name pncp-licitacoes.com;

    root /var/www/html/public;
    index index.php;

    # Redirecionar HTTP para HTTPS
    if ($scheme != "https") {
        return 301 https://$server_name$request_uri;
    }

    # Rewrite URLs
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP Handler
    location ~ \.php$ {
        fastcgi_pass php;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_buffering off;
    }

    # Cache estático (1 ano)
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 365d;
        add_header Cache-Control "public, immutable";
    }

    # Block .env access
    location ~ /\.env {
        deny all;
    }

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
}
```

### 2. Apache Configuration

```apache
<VirtualHost *:80>
    ServerName pncp-licitacoes.com
    ServerAlias www.pncp-licitacoes.com

    DocumentRoot /var/www/html/public
    
    <Directory /var/www/html/public>
        AllowOverride All
        Order Allow,Deny
        Allow from all
        
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_FILENAME} !-d
            RewriteRule ^ /index.php [L]
        </IfModule>
    </Directory>

    # Redirecionar HTTP para HTTPS
    Redirect permanent / https://pncp-licitacoes.com/

    ErrorLog ${APACHE_LOG_DIR}/pncp-error.log
    CustomLog ${APACHE_LOG_DIR}/pncp-access.log combined
</VirtualHost>

<VirtualHost *:443>
    ServerName pncp-licitacoes.com
    ServerAlias www.pncp-licitacoes.com

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/certificate.crt
    SSLCertificateKeyFile /etc/ssl/private/private.key
    SSLCertificateChainFile /etc/ssl/certs/chain.crt

    DocumentRoot /var/www/html/public
    
    <Directory /var/www/html/public>
        AllowOverride All
        Order Allow,Deny
        Allow from all
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/pncp-error.log
    CustomLog ${APACHE_LOG_DIR}/pncp-access.log combined
</VirtualHost>
```

### 3. Variáveis de Ambiente (Produção)

```bash
# .env.production
CI_ENVIRONMENT=production
app.baseURL=https://pncp-licitacoes.com

# Database
database.default.hostname=db.prod.internal
database.default.username=pncp_prod_user
database.default.password=SENHA_COMPLEXA_AQUI

# Cache
cache.handler=redis
cache.redis.host=redis.prod.internal
cache.redis.port=6379

# Security
app.CSRFProtection=true
app.CSRFTokenName=csrf_token
app.sessionCookieSecure=true
app.sessionCookieSameSite=Strict

# Logging
logger.threshold=2
```

## 📊 Monitoramento

### Health Check Endpoint

```php
// app/Controllers/HealthController.php
<?php
namespace App\Controllers;

class HealthController extends BaseController
{
    public function check()
    {
        $db = \Config\Database::connect();
        $cache = \Config\Services::cache();
        
        $health = [
            'status' => 'healthy',
            'timestamp' => date('Y-m-d H:i:s'),
            'database' => 'ok',
            'cache' => 'ok',
        ];
        
        try {
            $db->query('SELECT 1');
        } catch (\Exception $e) {
            $health['status'] = 'unhealthy';
            $health['database'] = 'error: ' . $e->getMessage();
        }
        
        try {
            $cache->get('health-check');
        } catch (\Exception $e) {
            $health['cache'] = 'error';
        }
        
        $code = $health['status'] === 'healthy' ? 200 : 503;
        return $this->response->setJSON($health)->setStatusCode($code);
    }
}
?>
```

### Logs Estruturados

```php
// Usar Monolog para logs estruturados
$logger = service('logger');
$logger->info('Sincronização iniciada', [
    'user_id' => $userId,
    'dias' => $dias,
    'timestamp' => date('Y-m-d H:i:s')
]);
```

## 🚀 CI/CD Pipeline (GitHub Actions)

```yaml
name: CI/CD

on:
  push:
    branches: [ main, develop ]
  pull_request:
    branches: [ main, develop ]

jobs:
  test:
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_DATABASE: pncp_test
          MYSQL_USER: test_user
          MYSQL_PASSWORD: test_pass
          MYSQL_ROOT_PASSWORD: root
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3

    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: 8.1
        extensions: mbstring, curl, json, intl
    
    - name: Install deps
      run: composer install
    
    - name: Run migrations
      run: php spark migrate
      env:
        DB_HOST: localhost
        DB_USER: test_user
        DB_PASSWORD: test_pass
        DB_NAME: pncp_test
    
    - name: Run tests
      run: vendor/bin/phpunit
    
    - name: Run lint
      run: vendor/bin/phpcs --standard=PSR12 app/

  deploy:
    needs: test
    runs-on: ubuntu-latest
    if: github.ref == 'refs/heads/main'
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Deploy to server
      uses: appleboy/ssh-action@master
      with:
        host: ${{ secrets.HOST }}
        username: ${{ secrets.SSH_USER }}
        key: ${{ secrets.SSH_KEY }}
        script: |
          cd /var/www/html
          git pull origin main
          composer install --no-dev
          php spark migrate
          php spark cache:clear
```

## 📈 Performance Optimization

### 1. Cache Strategy

```php
// Cachear dados pesados
$stats = cache('licitacoes:stats');
if (!$stats) {
    $stats = $this->licitacaoRepo->getEstatisticasGerais();
    cache()->save('licitacoes:stats', $stats, 3600); // 1 hora
}
return $stats;
```

### 2. Database Optimization

```sql
-- Índices para queries frequentes
CREATE INDEX idx_user_orgao ON licitacoes(orgao_id);
CREATE INDEX idx_date_created ON licitacoes(created_at);
CREATE INDEX idx_modalidade_status ON licitacoes(modalidade, situacao);
CREATE FULLTEXT INDEX idx_objeto ON licitacoes(objeto);
```

### 3. API Rate Limiting

```php
// Implementar rate limiting
$rateLimit = new RateLimiter('api', [
    'windowSize' => 3600, // 1 hora
    'maxRequests' => 1000 // 1000 requisições por hora
]);

if (!$rateLimit->allow($userId)) {
    throw new \Exception('Rate limit exceeded');
}
```

## 🔐 Segurança

### SSL/TLS Certificate (Let's Encrypt)

```bash
# Instalar Certbot
sudo apt-get install certbot python3-certbot-nginx

# Gerar certificado
sudo certbot certonly --nginx -d pncp-licitacoes.com

# Auto-renew
sudo systemctl enable certbot.timer
sudo systemctl start certbot.timer
```

### Firewall Rules

```bash
# UFW (Ubuntu)
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw allow 3306/tcp # Apenas da aplicação
sudo ufw enable
```

### Database Backup

```bash
#!/bin/bash
# backup.sh

BACKUP_DIR="/backups/mysql"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="pncp_licitacoes"

mkdir -p $BACKUP_DIR

mysqldump -u $DB_USER -p$DB_PASSWORD $DB_NAME > $BACKUP_DIR/backup_$DATE.sql

# Comprimir
gzip $BACKUP_DIR/backup_$DATE.sql

# Manter apenas últimos 7 dias
find $BACKUP_DIR -mtime +7 -delete

echo "Backup concluído: $BACKUP_DIR/backup_$DATE.sql.gz"
```

## 📋 Deployment Checklist

- [ ] Variáveis de ambiente configuradas
- [ ] Banco de dados criado e migrado
- [ ] SSL/TLS certificado instalado
- [ ] Credenciais de API configuradas
- [ ] Logs configurados
- [ ] Backups automáticos configurados
- [ ] Monitoramento ativo
- [ ] Health check endpoint testado
- [ ] Rate limiting configurado
- [ ] Cache redis ativo
- [ ] Cron jobs configurados para sincronização
- [ ] CDN configurado para assets estáticos

## 🚨 Troubleshooting Produção

### Erro: 502 Bad Gateway
- Verificar conexão com pool PHP
- Verificar logs de erro
- Aumentar PHP-FPM workers

### Erro: Database Connection Lost
- Verificar credenciais de BD
- Verificar firewall
- Reconnect pool

### Erro: Out of Memory
- Aumentar PHP memory_limit
- Otimizar queries
- Implementar pagination

## 📞 Suporte

Para issues em produção:
1. Verificar logs: `/var/log/pncp/`
2. Health check: `curl https://pncp-licitacoes.com/health`
3. Database status: `mysql -u user -p -e "SELECT 1;"`
4. Redis status: `redis-cli ping`

