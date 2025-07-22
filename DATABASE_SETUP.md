# 🗄️ GUIA DE CONFIGURAÇÃO DO BANCO DE DADOS
## Sistema Diário de Bordo

Este guia contém todas as instruções para configurar o banco de dados MySQL/MariaDB para o sistema Diário de Bordo.

## 📋 Pré-requisitos

- ✅ MySQL 8.0+ ou MariaDB 10.4+
- ✅ PHP 8.0+
- ✅ Composer instalado
- ✅ Acesso ao MySQL como root ou usuário com privilégios

## 🚀 Método 1: Configuração Automática (Recomendado)

### Passo 1: Execute o script SQL
```bash
# Entre no MySQL como root
mysql -u root -p

# Execute o script de criação
mysql -u root -p < database_setup.sql
```

**OU execute diretamente:**
```sql
source database_setup.sql;
```

### Passo 2: Configure o arquivo .env
```bash
# Copie o arquivo de exemplo
cp .env.example .env

# Edite as configurações do banco
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=diario_bordo
DB_USERNAME=root
DB_PASSWORD=sua_senha_mysql
```

### Passo 3: Configure a aplicação Laravel
```bash
# Gere a chave da aplicação
php artisan key:generate

# Teste a conexão
php artisan migrate:status
```

## 🛠️ Método 2: Configuração Manual

### Passo 1: Criar o banco de dados
```sql
CREATE DATABASE diario_bordo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE diario_bordo;
```

### Passo 2: Executar migrations do Laravel
```bash
# Execute as migrations
php artisan migrate

# Execute os seeders para dados iniciais
php artisan db:seed
```

### Passo 3: Criar usuário administrador
```bash
php artisan db:seed --class=AdminUserSeeder
```

## 📊 Estrutura do Banco Criada

### Tabelas Principais:
- ✅ **users** - Usuários do sistema
- ✅ **viagems** - Registro de viagens
- ✅ **sugestoes** - Sugestões e contatos
- ✅ **favoritos** - Viagens favoritadas
- ✅ **push_subscriptions** - Notificações push
- ✅ **notifications** - Sistema de notificações

### Dados de Exemplo Inclusos:
- 👤 **4 usuários** (1 admin + 3 usuários comuns)
- 🚗 **8 viagens de exemplo** com diferentes status
- 💭 **3 sugestões** para testar o sistema
- ⭐ **6 favoritos** vinculados aos usuários

## 🔐 Credenciais de Acesso

### Administrador:
- **Email**: admin@diariobordo.com
- **Senha**: password
- **Permissões**: Administrador completo

### Usuários de Teste:
- **João Silva**: joao@diariobordo.com | password
- **Maria Santos**: maria@diariobordo.com | password  
- **Carlos Oliveira**: carlos@diariobordo.com | password

## 📈 Views e Relatórios

O script cria automaticamente views úteis:

### vw_estatisticas_usuario
```sql
SELECT * FROM vw_estatisticas_usuario;
-- Mostra estatísticas consolidadas por usuário
```

### vw_destinos_populares
```sql
SELECT * FROM vw_destinos_populares;
-- Lista os destinos mais visitados
```

## 🔧 Comandos Úteis de Manutenção

### Verificar status das migrations:
```bash
php artisan migrate:status
```

### Recriar banco (CUIDADO - apaga tudo):
```bash
php artisan migrate:fresh --seed
```

### Backup do banco:
```bash
mysqldump -u root -p diario_bordo > backup_$(date +%Y%m%d).sql
```

### Restaurar backup:
```bash
mysql -u root -p diario_bordo < backup_20250722.sql
```

## 🐛 Solução de Problemas

### Erro de Conexão:
```bash
# Teste a conexão MySQL
mysql -u root -p

# Verifique se o serviço está rodando
systemctl status mysql   # Linux
brew services list | grep mysql   # macOS
```

### Erro de Permissões:
```sql
-- Criar usuário específico para o Laravel
CREATE USER 'laravel'@'localhost' IDENTIFIED BY 'senha_segura';
GRANT ALL PRIVILEGES ON diario_bordo.* TO 'laravel'@'localhost';
FLUSH PRIVILEGES;
```

### Erro de Charset:
```sql
-- Verificar charset do banco
SELECT DEFAULT_CHARACTER_SET_NAME, DEFAULT_COLLATION_NAME 
FROM information_schema.SCHEMATA 
WHERE SCHEMA_NAME = 'diario_bordo';
```

## 📱 Configurações Especiais

### Para PWA e Notificações Push:
As tabelas `push_subscriptions` e `notifications` já estão configuradas para suportar:
- 🔔 Notificações web push
- 📱 Progressive Web App
- 🔄 Sincronização offline

### Para Upload de Arquivos:
Configure no `.env`:
```bash
FILESYSTEM_DISK=local
MAX_UPLOAD_SIZE=10240
ALLOWED_FILE_TYPES="jpg,jpeg,png,pdf,doc,docx"
```

## ✅ Verificação Final

Execute estes comandos para verificar se tudo está funcionando:

```bash
# 1. Verificar conexão com banco
php artisan tinker
>>> DB::connection()->getPdo();

# 2. Contar registros nas tabelas principais
>>> App\Models\User::count();  // Deve retornar 4
>>> App\Models\Viagem::count(); // Deve retornar 8

# 3. Testar usuário admin
>>> App\Models\User::where('is_admin', true)->first();

# 4. Iniciar servidor
php artisan serve --host=0.0.0.0 --port=8000
```

## 🎯 Próximos Passos

Após configurar o banco:

1. ✅ **Acesse**: http://localhost:8000
2. ✅ **Faça login** com credenciais de admin
3. ✅ **Teste funcionalidades**:
   - Criar nova viagem
   - Gerar relatórios
   - Navegar entre páginas
4. ✅ **Configure notificações push** (opcional)
5. ✅ **Personalize dados** conforme necessário

## 📞 Suporte

Se encontrar problemas:
1. Consulte os logs: `storage/logs/laravel.log`
2. Verifique configurações do `.env`
3. Teste conexão MySQL separadamente
4. Consulte documentação do Laravel: https://laravel.com/docs

---

**Sistema Diário de Bordo v1.0**  
*Configuração completa do banco de dados* 🚀
