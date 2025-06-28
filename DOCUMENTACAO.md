# Documentação do Sistema Diário de Bordo

## Índice
1. [Visão Geral](#visão-geral)
2. [Arquitetura do Sistema](#arquitetura-do-sistema)
3. [Instalação e Configuração](#instalação-e-configuração)
4. [Funcionalidades](#funcionalidades)
5. [Estrutura do Banco de Dados](#estrutura-do-banco-de-dados)
6. [Guia do Usuário](#guia-do-usuário)
7. [Guia do Administrador](#guia-do-administrador)
8. [Segurança](#segurança)
9. [API e Endpoints](#api-e-endpoints)
10. [Comandos Artisan](#comandos-artisan)
11. [Troubleshooting](#troubleshooting)
12. [Atualizações e Manutenção](#atualizações-e-manutenção)

---

## Visão Geral

O **Diário de Bordo** é um sistema web desenvolvido em **Laravel 9** para gerenciamento e controle de viagens corporativas. O sistema permite o registro detalhado de viagens, controle de quilometragem, gestão de usuários e geração de relatórios.

### Tecnologias Utilizadas
- **Framework**: Laravel 9.x
- **PHP**: 8.0+
- **Frontend**: Bootstrap 5.3, Blade Templates
- **Banco de Dados**: MySQL/MariaDB
- **Autenticação**: Laravel Breeze
- **PDF**: DomPDF
- **Excel**: Maatwebsite Excel
- **Email**: Laravel Mail

### Principais Características
- ✅ Sistema de autenticação completo com verificação de email
- ✅ Dashboard interativo com estatísticas
- ✅ Cadastro e gerenciamento de viagens
- ✅ Sistema de permissões (Usuário/Administrador)
- ✅ Relatórios em PDF e Excel
- ✅ Interface responsiva
- ✅ Sistema de anexos para viagens
- ✅ Validações robustas
- ✅ Rate limiting para segurança

---

## Arquitetura do Sistema

### Estrutura MVC
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/                    # Controladores de autenticação
│   │   ├── ViagemController.php     # Gerenciamento de viagens
│   │   ├── RelatorioController.php  # Geração de relatórios
│   │   ├── AdminUserController.php  # Administração de usuários
│   │   └── ProfileController.php    # Perfil do usuário
│   ├── Middleware/
│   │   ├── AdminMiddleware.php      # Verificação de admin
│   │   └── ForcarTrocaSenha.php     # Força troca de senha
│   └── Requests/                    # Validações de formulários
├── Models/
│   ├── User.php                     # Modelo de usuário
│   ├── Viagem.php                   # Modelo de viagem
│   ├── Sugestao.php                 # Modelo de sugestões
│   └── Favorito.php                 # Modelo de favoritos
├── Services/
│   └── ViagemService.php            # Lógica de negócio
└── Mail/
    ├── BemVindoMail.php             # Email de boas-vindas
    └── RespostaSugestaoMail.php     # Email de resposta
```

### Middlewares Personalizados
- **AdminMiddleware**: Verifica se o usuário é administrador
- **ForcarTrocaSenha**: Força usuários a trocar senha no primeiro acesso
- **LogAdminActions**: Log de ações administrativas
- **CacheUserData**: Cache de dados do usuário

---

## Instalação e Configuração

### Pré-requisitos
- PHP 8.0 ou superior
- Composer
- MySQL/MariaDB
- Servidor web (Apache/Nginx)
- Node.js e NPM (para assets frontend)

### Passo a Passo

1. **Clone o repositório**
```bash
git clone [url-do-repositorio]
cd diario-bordo
```

2. **Instale as dependências**
```bash
composer install
npm install
```

3. **Configure o arquivo .env**
```bash
cp .env.example .env
```

Edite o arquivo `.env` com suas configurações:
```env
APP_NAME="Diário de Bordo"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=diario_bordo
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu-email@gmail.com
MAIL_FROM_NAME="Diário de Bordo"

# Para acesso via rede local
SESSION_DOMAIN=null
SANCTUM_STATEFUL_DOMAINS=192.168.1.100:8000,localhost:8000
```

4. **Gere a chave da aplicação**
```bash
php artisan key:generate
```

5. **Execute as migrations**
```bash
php artisan migrate
```

6. **Execute os seeders**
```bash
php artisan db:seed
```

7. **Crie o link simbólico para storage**
```bash
php artisan storage:link
```

8. **Compile os assets**
```bash
npm run build
```

9. **Inicie o servidor**
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

### Configuração para Rede Local

Para acesso via IP na rede local, certifique-se de configurar:

1. **SESSION_DOMAIN** como `null` no `.env`
2. **SANCTUM_STATEFUL_DOMAINS** incluindo o IP local
3. **Firewall** liberado para a porta 8000

---

## Funcionalidades

### Para Usuários Comuns

#### 1. Dashboard
- Estatísticas pessoais de viagens
- Gráfico de evolução mensal
- Atalhos rápidos para principais funcionalidades
- Modal de boas-vindas (pode ser desabilitado)

#### 2. Gerenciamento de Viagens
- **Cadastro**: Registro completo de viagens com validações
- **Listagem**: Visualização em cards modernos com status coloridos
- **Edição**: Atualização de informações
- **Visualização**: Detalhes completos da viagem
- **Filtros**: Por destino, data, tipo de veículo, placa, condutor
- **Anexos**: Upload de documentos relacionados

#### 3. Relatórios
- Exportação em PDF
- Exportação em Excel
- Filtros personalizáveis

#### 4. Perfil
- Edição de dados pessoais
- Upload de foto de perfil
- Alteração de senha

#### 5. Sugestões
- Sistema de feedback
- Contato com administradores

### Para Administradores

#### 1. Gerenciamento de Usuários
- Criação de novos usuários
- Edição de informações
- Redefinição de senhas
- Exclusão de usuários
- Controle de permissões administrativas

#### 2. Gerenciamento de Mensagens
- Visualização de sugestões
- Resposta por email
- Exclusão de mensagens

#### 3. Relatórios Globais
- Acesso a relatórios de todos os usuários
- Filtros avançados

---

## Estrutura do Banco de Dados

### Tabela: users
```sql
- id (Primary Key)
- name (VARCHAR 255)
- email (VARCHAR 255, UNIQUE)
- email_verified_at (TIMESTAMP, NULLABLE)
- password (VARCHAR 255)
- telefone (VARCHAR 20, NULLABLE)
- cargo (VARCHAR 100, NULLABLE)
- data_nascimento (DATE, NULLABLE)
- foto_perfil (VARCHAR 255, NULLABLE)
- cpf (VARCHAR 14, UNIQUE, NULLABLE)
- is_admin (BOOLEAN, DEFAULT FALSE)
- precisa_trocar_senha (BOOLEAN, DEFAULT FALSE)
- remember_token (VARCHAR 100, NULLABLE)
- timestamps
```

### Tabela: viagens
```sql
- id (Primary Key)
- data (DATE)
- hora_saida (TIME)
- km_saida (INTEGER)
- destino (VARCHAR 255)
- hora_chegada (TIME, NULLABLE)
- km_chegada (INTEGER, NULLABLE)
- condutor (VARCHAR 255)
- user_id (Foreign Key -> users.id)
- num_registro_abastecimento (VARCHAR 100, NULLABLE)
- quantidade_abastecida (DECIMAL 8,2, NULLABLE)
- tipo_veiculo (VARCHAR 100, NULLABLE)
- placa (VARCHAR 10, NULLABLE)
- checklist (JSON, NULLABLE)
- anexos (JSON, NULLABLE)
- timestamps
```

### Tabela: sugestoes
```sql
- id (Primary Key)
- nome (VARCHAR 255)
- email (VARCHAR 255)
- tipo (ENUM: 'sugestao', 'contato')
- mensagem (TEXT)
- user_id (Foreign Key -> users.id, NULLABLE)
- timestamps
```

### Tabela: favoritos
```sql
- id (Primary Key)
- user_id (Foreign Key -> users.id)
- viagem_id (Foreign Key -> viagens.id)
- timestamps
```

---

## Guia do Usuário

### Primeiro Acesso

1. **Registro**: Criar conta com email válido
2. **Verificação**: Confirmar email através do link enviado
3. **Login**: Acessar o sistema com credenciais
4. **Dashboard**: Familiarizar-se com a interface

### Cadastrando uma Viagem

1. Acesse **Dashboard > Nova Viagem** ou **Viagens > Nova Viagem**
2. Preencha os campos obrigatórios:
   - Data (deve ser hoje)
   - Hora de saída
   - KM de saída
   - Destino
   - Condutor
3. Campos opcionais:
   - Hora de chegada
   - KM de chegada
   - Registro de abastecimento
   - Quantidade abastecida
   - Tipo de veículo
   - Placa
   - Checklist
   - Anexos

### Gerenciando Viagens

- **Filtrar**: Use os filtros no topo da listagem
- **Visualizar**: Clique no card da viagem para ver detalhes
- **Editar**: Use o botão de edição
- **Status**: 
  - 🟢 Verde: Viagem concluída (tem KM chegada)
  - 🟡 Amarelo: Em andamento (sem KM chegada)
  - 🔵 Azul: Agendada (data futura)

### Gerando Relatórios

1. Acesse **Relatórios**
2. Aplique filtros desejados:
   - Usuário (apenas admins)
   - Data inicial/final
   - Tipo de veículo
   - Placa
3. Escolha o formato: PDF ou Excel
4. Clique em **Gerar Relatório**

---

## Guia do Administrador

### Criando Usuários

1. Acesse **Usuários > Novo Usuário**
2. Preencha os dados obrigatórios:
   - Nome
   - Email
3. Dados opcionais:
   - Telefone
   - Cargo
   - Data de nascimento
   - CPF
   - Permissão de administrador
4. Sistema gera senha automática e envia por email

### Gerenciando Usuários

- **Listar**: Visualize todos os usuários
- **Editar**: Atualize informações
- **Resetar Senha**: Gere nova senha automática
- **Toggle Admin**: Alterne permissões administrativas
- **Excluir**: Remove usuário do sistema

### Respondendo Mensagens

1. Acesse **Mensagens**
2. Visualize sugestões e contatos
3. Use o botão **Responder** para enviar email
4. Exclua mensagens processadas

---

## Segurança

### Autenticação
- Hash seguro de senhas (bcrypt)
- Verificação obrigatória de email
- Sistema de "lembrar-me"
- Logout automático por inatividade

### Autorização
- Middleware de administrador
- Proteção de rotas sensíveis
- Verificação de propriedade de recursos

### Rate Limiting
- Login: 5 tentativas por minuto por IP
- Registro: 3 registros por minuto por IP
- Sugestões: 5 mensagens por minuto por usuário
- API: 60 requests por minuto

### Validações
- CSRF protection em formulários
- Validação de tipos de arquivo
- Sanitização de inputs
- Validação de datas e horários

### Headers de Segurança
```php
// Configurados no middleware
X-Frame-Options: DENY
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
```

---

## API e Endpoints

### Rotas de Autenticação
```
GET  /login              # Tela de login
POST /login              # Processar login
GET  /register           # Tela de registro
POST /register           # Processar registro
POST /logout             # Logout
GET  /forgot-password    # Recuperar senha
POST /forgot-password    # Enviar link recuperação
```

### Rotas de Viagens
```
GET    /viagens           # Listar viagens
GET    /viagens/create    # Formulário nova viagem
POST   /viagens           # Salvar viagem
GET    /viagens/{id}      # Detalhes da viagem
GET    /viagens/{id}/edit # Formulário edição
PUT    /viagens/{id}      # Atualizar viagem
DELETE /viagens/{id}      # Excluir viagem
```

### Rotas Administrativas
```
GET    /admin/users              # Listar usuários
GET    /admin/users/create       # Criar usuário
POST   /admin/users              # Salvar usuário
GET    /admin/users/{id}/edit    # Editar usuário
PUT    /admin/users/{id}         # Atualizar usuário
DELETE /admin/users/{id}         # Excluir usuário
POST   /admin/users/{id}/reset-password # Resetar senha
```

### API AJAX
```
GET /viagens?page=N&ajax=1    # Paginação infinita
POST /admin/users/{id}/toggle-admin # Toggle admin via AJAX
```

---

## Comandos Artisan

### Comandos Básicos
```bash
# Limpar caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Otimizações para produção
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Migrations
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh --seed

# Seeders
php artisan db:seed
php artisan db:seed --class=AdminUserSeeder

# Storage
php artisan storage:link

# Queue (se configurado)
php artisan queue:work
php artisan queue:restart
```

### Comandos Personalizados
```bash
# Criar backup do banco
php artisan backup:run

# Limpar logs antigos
php artisan logs:clear

# Verificar integridade do sistema
php artisan system:check
```

---

## Troubleshooting

### Problemas Comuns

#### 1. Erro 419 (Page Expired)
**Sintomas**: Formulários retornam erro 419
**Solução**:
```bash
php artisan config:clear
php artisan cache:clear
```
Verifique se `APP_KEY` está configurada no `.env`

#### 2. Erro de Permissão de Storage
**Sintomas**: Uploads falham ou imagens não carregam
**Solução**:
```bash
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
php artisan storage:link
```

#### 3. Erro de Conexão com Banco
**Sintomas**: SQLSTATE[HY000] [2002]
**Solução**:
- Verifique credenciais no `.env`
- Confirme se o MySQL está rodando
- Teste conexão manual

#### 4. Emails Não Enviados
**Sintomas**: Emails de verificação não chegam
**Solução**:
- Configure SMTP corretamente no `.env`
- Use senha de app para Gmail
- Verifique logs em `storage/logs/`

#### 5. Erro 500 em Produção
**Sintomas**: Página em branco ou erro 500
**Solução**:
```bash
php artisan config:cache
php artisan optimize
chmod -R 775 storage/
```
Verifique logs em `storage/logs/laravel.log`

### Logs Importantes
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Apache/Nginx logs
tail -f /var/log/apache2/error.log
tail -f /var/log/nginx/error.log

# MySQL logs
tail -f /var/log/mysql/error.log
```

### Monitoramento

#### Performance
```bash
# Verificar uso de memória
php artisan tinker
>>> memory_get_usage(true)

# Cache status
php artisan config:show cache
```

#### Banco de Dados
```sql
-- Verificar tamanho das tabelas
SELECT 
    table_name AS 'Tabela',
    round(((data_length + index_length) / 1024 / 1024), 2) AS 'Tamanho (MB)'
FROM information_schema.tables 
WHERE table_schema = 'diario_bordo'
ORDER BY (data_length + index_length) DESC;

-- Verificar índices
SHOW INDEX FROM viagens;
```

---

## Atualizações e Manutenção

### Rotina de Manutenção

#### Diária
- Verificar logs de erro
- Monitorar espaço em disco
- Backup automático do banco de dados

#### Semanal
- Limpar cache de visualizações antigas
- Verificar integridade de anexos
- Analisar relatórios de uso

#### Mensal
- Atualizar dependências do Composer
- Revisar logs de segurança
- Otimizar banco de dados

### Backup

#### Banco de Dados
```bash
# Backup
mysqldump -u root -p diario_bordo > backup_$(date +%Y%m%d).sql

# Restauração
mysql -u root -p diario_bordo < backup_20231201.sql
```

#### Arquivos
```bash
# Backup completo
tar -czf diario_bordo_backup_$(date +%Y%m%d).tar.gz \
  --exclude=node_modules \
  --exclude=vendor \
  /path/to/diario-bordo/

# Backup apenas uploads
tar -czf uploads_backup_$(date +%Y%m%d).tar.gz storage/app/public/
```

### Atualizações

#### Atualizar Laravel
```bash
# Verificar versão atual
php artisan --version

# Atualizar dependências
composer update

# Executar migrations
php artisan migrate

# Limpar caches
php artisan optimize:clear
php artisan optimize
```

#### Atualizar Frontend
```bash
npm update
npm run build
```

### Deploy em Produção

#### Checklist de Deploy
- [ ] Backup do banco de dados
- [ ] Backup dos arquivos
- [ ] Testar em ambiente de staging
- [ ] Atualizar código via Git
- [ ] Executar `composer install --no-dev`
- [ ] Executar migrations
- [ ] Limpar e recriar caches
- [ ] Verificar permissões de arquivos
- [ ] Testar funcionalidades críticas

#### Script de Deploy
```bash
#!/bin/bash
cd /var/www/diario-bordo

# Backup
mysqldump -u root -p$DB_PASS diario_bordo > backup_$(date +%Y%m%d_%H%M%S).sql

# Atualizar código
git pull origin main

# Dependências
composer install --no-dev --optimize-autoloader

# Migrations
php artisan migrate --force

# Cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Permissões
chmod -R 775 storage bootstrap/cache

echo "Deploy concluído!"
```

---

## Suporte e Contato

### Informações do Sistema
- **Versão**: 1.0.0
- **Desenvolvido por**: DITIS-ELN
- **Laravel**: 9.x
- **PHP**: 8.0+

### Para Suporte Técnico
1. Verifique esta documentação primeiro
2. Consulte os logs do sistema
3. Entre em contato com a equipe de desenvolvimento

### Melhorias Futuras
- [ ] API REST completa
- [ ] App mobile (PWA)
- [ ] Integração com GPS
- [ ] Notificações push
- [ ] Dashboard analytics avançado
- [ ] Integração com sistemas externos

---

*Esta documentação foi gerada em: {{ date('d/m/Y H:i') }}*
**Framework**: Laravel 10.x
