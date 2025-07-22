# 🚂 Configuração no Railway

## Passo a Passo para Deploy

### 1. Criar Conta no Railway
1. Acesse [railway.app](https://railway.app)
2. Faça login com GitHub
3. Conecte seu repositório `DIARIO-DE-BORDO`

### 2. Configurar o Banco de Dados

#### Opção A: MySQL no Railway
1. No dashboard do Railway, clique em **"New Project"**
2. Selecione **"Deploy from GitHub repo"**
3. Escolha o repositório `elneves81/DIARIO-DE-BORDO`
4. Após o deploy inicial, clique em **"+ New"** → **"Database"** → **"Add MySQL"**

#### Opção B: PostgreSQL (Recomendado)
1. Clique em **"+ New"** → **"Database"** → **"Add PostgreSQL"**
2. Railway criará automaticamente um banco PostgreSQL

### 3. Configurar Variáveis de Ambiente

No Railway, vá em **Variables** e configure:

```env
# App Configuration
APP_NAME="Diário de Bordo"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-app.railway.app

# Database (será preenchido automaticamente pelo Railway)
DATABASE_URL=postgresql://username:password@host:port/database
DB_CONNECTION=pgsql
DB_HOST=containers-us-west-x.railway.app
DB_PORT=5432
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=senha-gerada-automaticamente

# Email Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu-email@gmail.com
MAIL_FROM_NAME="Diário de Bordo"

# Security
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_DOMAIN=.railway.app

# Cache
CACHE_DRIVER=database
QUEUE_CONNECTION=database

# VAPID Keys (para notificações push)
VAPID_PUBLIC_KEY="BEl62iUYgUivxIkv69yViEuiBIa40HI80NMEy_qzlJNgzq2BPZFhC_xDUGGsIhm7YLRQcKGfLUBSsD_gZlDtNNw"
VAPID_PRIVATE_KEY="nNNF5p2xhwUFHQzNX7eDXJhAEoSCIE7GlWLX4TZdLuE"
VAPID_SUBJECT="mailto:admin@diariobordo.railway.app"
```

### 4. Configurar Build e Deploy

O Railway detectará automaticamente que é um projeto Laravel. Se necessário, adicione um arquivo `railway.toml`:

```toml
[build]
builder = "nixpacks"

[deploy]
startCommand = "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"
```

### 5. Executar Migrations e Seeders

Após o primeiro deploy:

1. Vá em **Deployments** → **View Logs**
2. Abra o **Terminal** do Railway
3. Execute os comandos:

```bash
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder
php artisan storage:link
php artisan config:cache
php artisan route:cache
```

### 6. Popular o Banco com Dados de Exemplo

Execute o script SQL que criamos:

```bash
# Baixar o arquivo SQL do GitHub
curl -o database_setup.sql https://raw.githubusercontent.com/elneves81/DIARIO-DE-BORDO/main/database_setup.sql

# Executar no banco PostgreSQL (adaptar comandos MySQL para PostgreSQL)
psql $DATABASE_URL < database_setup_postgres.sql
```

## Configurações Específicas para Railway

### 1. Arquivo `Procfile` (opcional)
```
web: php artisan serve --host=0.0.0.0 --port=$PORT
```

### 2. Configuração de Assets
O Railway compila automaticamente os assets com Vite. Certifique-se que o `package.json` tenha:

```json
{
  "scripts": {
    "build": "vite build",
    "postinstall": "npm run build"
  }
}
```

### 3. Configuração de Storage
Para uploads de arquivos, configure o storage para usar `public` disk:

```php
// config/filesystems.php
'default' => env('FILESYSTEM_DISK', 'public'),
```

## Comandos Úteis no Railway

### Acessar Terminal
1. Railway Dashboard → Seu Projeto → **Terminal**

### Ver Logs
1. Railway Dashboard → **Deployments** → **View Logs**

### Conectar ao Banco
```bash
# Para PostgreSQL
psql $DATABASE_URL

# Para MySQL
mysql -h $DB_HOST -P $DB_PORT -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE
```

### Executar Comandos Laravel
```bash
php artisan migrate
php artisan db:seed
php artisan cache:clear
php artisan config:cache
php artisan storage:link
```

## Vantagens do Railway

✅ **Deploy Automático**: Conecta direto com GitHub
✅ **Banco Incluído**: PostgreSQL/MySQL incluído no plano
✅ **SSL Grátis**: HTTPS automático
✅ **Domínio Grátis**: subdomínio .railway.app
✅ **Logs em Tempo Real**: Monitoramento fácil
✅ **Scaling Automático**: Escala conforme necessário
✅ **Backup Automático**: Backup do banco incluído

## Custos Aproximados

- **Hobby Plan**: $5/mês (ideal para desenvolvimento)
- **Pro Plan**: $20/mês (produção pequena/média)
- **Includes**: 
  - Banco de dados
  - 100GB de transferência
  - SSL certificates
  - Custom domains

## Domínio Personalizado (Opcional)

1. Railway Dashboard → **Settings** → **Domains**
2. Adicione seu domínio personalizado
3. Configure os DNS records:
   ```
   CNAME: seu-dominio.com → seu-app.railway.app
   ```

## Monitoramento

### Logs da Aplicação
```bash
# Ver logs em tempo real
railway logs

# Ver logs específicos
railway logs --service=web
```

### Métricas
- CPU Usage
- Memory Usage
- Network Traffic
- Database Connections

Tudo disponível no dashboard do Railway.

## Backup e Restauração

### Backup Automático
O Railway faz backup automático do banco. Para backup manual:

```bash
# PostgreSQL
pg_dump $DATABASE_URL > backup.sql

# MySQL
mysqldump -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE > backup.sql
```

### Restauração
```bash
# PostgreSQL
psql $DATABASE_URL < backup.sql

# MySQL
mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE < backup.sql
```

## Troubleshooting Railway

### 1. Build Falha
- Verifique `composer.json` e `package.json`
- Logs no Railway Dashboard

### 2. Erro de Conexão com Banco
- Verifique variáveis `DATABASE_URL` e `DB_*`
- Teste conexão no terminal

### 3. Assets Não Carregam
- Execute `npm run build`
- Verifique `vite.config.js`

### 4. Erro 500
- Verifique logs: `railway logs`
- Execute: `php artisan config:cache`

### 5. Migrations Falham
- Execute manualmente: `php artisan migrate --force`
- Verifique sintaxe PostgreSQL vs MySQL

## Next Steps

Após configurar no Railway:

1. ✅ Testar todas as funcionalidades
2. ✅ Configurar monitoramento
3. ✅ Configurar backups regulares
4. ✅ Documentar URLs e credenciais
5. ✅ Configurar domínio personalizado (opcional)

---

**Railway é uma excelente escolha para o Diário de Bordo! Deploy simples, banco incluído e monitoramento integrado.**
