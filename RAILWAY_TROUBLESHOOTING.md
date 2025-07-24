# 🚂 Railway Deploy - Troubleshooting em Tempo Real

## ✅ O que já fizemos:
- ✅ Projeto conectado ao GitHub
- ✅ Arquivos de configuração criados
- ✅ Database script preparado

## 🔍 Status Atual do Deploy:

### 1. Build Logs - O que verificar:
```bash
# Se aparecer erro de Node.js:
- Verificar se está usando Node 18+
- package.json deve ter "engines" configurado

# Se aparecer erro de dependências:
- npm install deve executar com sucesso
- Verificar se todas as deps estão no package.json

# Se aparecer erro de build:
- vite build deve compilar os assets
- Verificar se tailwind.config.js existe
```

### 2. Database Setup - Próximos passos:

#### A. Adicionar PostgreSQL:
1. No Railway Dashboard → **"+ New"** → **"Database"** → **"Add PostgreSQL"**
2. Aguardar a criação (1-2 minutos)
3. Railway preencherá automaticamente `DATABASE_URL`

#### B. Configurar Variáveis:
Vá em **Variables** e adicione:

```env
APP_NAME=Diário de Bordo
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seu-dominio.railway.app

# Email (configure com seus dados)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu-email@gmail.com
MAIL_FROM_NAME=Diário de Bordo

# Cache e Session
SESSION_DRIVER=database
CACHE_DRIVER=database
QUEUE_CONNECTION=database

# VAPID para notificações
VAPID_PUBLIC_KEY=BEl62iUYgUivxIkv69yViEuiBIa40HI80NMEy_qzlJNgzq2BPZFhC_xDUGGsIhm7YLRQcKGfLUBSsD_gZlDtNNw
VAPID_PRIVATE_KEY=nNNF5p2xhwUFHQzNX7eDXJhAEoSCIE7GlWLX4TZdLuE
VAPID_SUBJECT=mailto:admin@diariobordo.railway.app
```

### 3. Após Deploy com Sucesso:

#### A. Setup do Banco:
```bash
# No Terminal do Railway:
php artisan migrate --force
php artisan key:generate
php artisan storage:link
php artisan config:cache
```

#### B. Importar Dados de Exemplo:
```bash
# Baixar e executar o script PostgreSQL:
curl -o database_setup_postgres.sql https://raw.githubusercontent.com/elneves81/DIARIO-DE-BORDO/main/database_setup_postgres.sql

# Executar no banco (pode dar erro de sintaxe - normal):
psql $DATABASE_URL < database_setup_postgres.sql

# Ou usar apenas as migrations do Laravel:
php artisan migrate --force
php artisan db:seed
```

## 🚨 Problemas Comuns e Soluções:

### ❌ Build Failed - Node.js
**Erro:** `Node.js version not supported`
**Solução:** 
- Verificar se package.json tem `"engines": {"node": ">=18.0.0"}`
- Railway detectará automaticamente

### ❌ Build Failed - Dependencies  
**Erro:** `npm install failed`
**Solução:**
- Verificar package.json
- Remover package-lock.json se necessário
- Railway executará npm install automaticamente

### ❌ Build Failed - Vite
**Erro:** `vite build failed`
**Solução:**
- Verificar se vite.config.js existe
- Verificar se tailwind.config.js existe
- Assets devem estar em resources/

### ❌ Database Connection
**Erro:** `SQLSTATE[08006] connection failed`
**Solução:**
- Verificar se PostgreSQL foi adicionado
- Variável DATABASE_URL deve estar preenchida
- Tentar reconectar o banco

### ❌ Laravel Key Missing
**Erro:** `No application encryption key`
**Solução:**
```bash
php artisan key:generate
```

### ❌ Storage Permissions
**Erro:** `Permission denied storage/`
**Solução:**
```bash
php artisan storage:link
```

## 📱 Testando a Aplicação:

### 1. Acesso Básico:
- URL do Railway deve carregar a página inicial
- Navegação entre páginas deve funcionar
- Assets (CSS/JS) devem carregar

### 2. Funcionalidades:
- Login/Register (após migrations)
- Dashboard com dados
- Navegação completa

### 3. Database:
- Migrations executadas
- Dados de exemplo (opcional)
- Conexão estável

## 🎯 Status Atual:

- [ ] Build concluído com sucesso
- [ ] PostgreSQL adicionado e conectado
- [ ] Variáveis de ambiente configuradas
- [ ] Migrations executadas
- [ ] Aplicação acessível via URL
- [ ] Teste de funcionalidades básicas

## 📞 Se precisar de ajuda:

1. **Build Logs:** Railway Dashboard → Deployments → View Logs
2. **Database Logs:** Railway Dashboard → PostgreSQL → Connect → Logs
3. **Laravel Logs:** Terminal → `cat storage/logs/laravel.log`

## 🎉 Quando tudo estiver funcionando:

✅ **Aplicação online:** https://seu-app.railway.app
✅ **Banco de dados:** PostgreSQL funcionando
✅ **Sistema completo:** Laravel + frontend estático
✅ **Próximo passo:** Configurar domínio personalizado (opcional)

---

**📊 Railway Status Dashboard: Monitore em tempo real**
**🔄 Auto-deploy: Toda alteração no GitHub será deployada automaticamente**
