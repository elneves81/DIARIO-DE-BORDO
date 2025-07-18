# 📚 Documentação Técnica - Diário de Bordo

## 🏗️ Arquitetura do Sistema

### Estrutura Organizada

O sistema foi completamente reorganizado seguindo as melhores práticas de desenvolvimento Laravel:

```
app/
├── Http/
│   ├── Controllers/           # Controllers organizados
│   ├── Requests/             # Form Requests por domínio
│   │   ├── Viagem/          # Validações de viagem
│   │   └── User/            # Validações de usuário
│   └── Middleware/          # Middlewares customizados
├── Models/                  # Models com scopes e accessors
├── Services/               # Lógica de negócio organizada
│   ├── Viagem/            # Serviços de viagem
│   ├── User/              # Serviços de usuário
│   └── Relatorio/         # Serviços de relatório
└── ...

resources/
├── css/
│   ├── app.css            # CSS principal
│   └── components/        # CSS por componente
│       └── dark-mode.css
├── js/
│   ├── app.js            # JavaScript principal
│   ├── bootstrap.js      # Configurações
│   └── modules/          # Módulos organizados
│       ├── dark-mode.js
│       ├── notifications.js
│       ├── advanced-search.js
│       ├── dashboard-analytics.js
│       └── ux-enhancements.js
└── views/                # Views organizadas
```

## 🔧 Services (Camada de Negócio)

### ViagemService
**Localização:** `app/Services/Viagem/ViagemService.php`

**Responsabilidades:**
- Validação de regras de negócio
- Cálculo de estatísticas
- Geração de relatórios mensais

**Métodos principais:**
```php
validateBusinessRules(array $data, ?Viagem $viagem = null): array
calculateStats(Viagem $viagem): array
generateMonthlyReport(int $userId, int $month, int $year): array
```

### UserService
**Localização:** `app/Services/User/UserService.php`

**Responsabilidades:**
- Criação e atualização de usuários
- Reset de senhas
- Validação de dados únicos
- Busca e estatísticas

**Métodos principais:**
```php
createUser(array $data): User
updateUser(User $user, array $data): User
resetPassword(User $user): string
toggleAdmin(User $user): User
```

### RelatorioService
**Localização:** `app/Services/Relatorio/RelatorioService.php`

**Responsabilidades:**
- Geração de relatórios com filtros
- Cálculo de estatísticas
- Formatação para exportação

**Métodos principais:**
```php
generateViagemReport(array $filters = []): Collection
calculateReportStats(Collection $viagens): array
generateDashboardData(int $userId = null): array
```

## 📝 Form Requests (Validações)

### Viagem Requests
- `StoreViagemRequest`: Validações para criação
- `UpdateViagemRequest`: Validações para atualização

**Características:**
- Validações customizadas de negócio
- Formatação automática de dados
- Mensagens de erro personalizadas
- Validação de CPF e placas

### User Requests
- `StoreUserRequest`: Validações para criação de usuários
- `UpdateUserRequest`: Validações para atualização

**Características:**
- Validação de CPF com algoritmo oficial
- Formatação automática de telefone
- Verificação de unicidade

## 🎯 Models Otimizados

### Viagem Model
**Localização:** `app/Models/Viagem.php`

**Melhorias implementadas:**
- **Scopes úteis:** `concluidas()`, `emAndamento()`, `agendadas()`
- **Accessors:** `status`, `distancia_percorrida`, `tempo_viagem`
- **Mutators:** Formatação automática de placa e tipo de veículo
- **Métodos auxiliares:** `canEdit()`, `canView()`, `getEstatisticas()`

**Exemplo de uso:**
```php
// Buscar viagens concluídas do usuário no mês atual
$viagens = Viagem::forUser($userId)
    ->concluidas()
    ->porMes(date('m'), date('Y'))
    ->ordenacaoPadrao()
    ->get();
```

## 🛣️ Rotas Organizadas

### Estrutura das Rotas
**Localização:** `routes/web.php`

**Organização:**
- **Rotas Públicas:** Home, offline
- **Rotas de Autenticação:** Login, registro
- **Rotas Autenticadas:** Dashboard, perfil, viagens, relatórios
- **Rotas Administrativas:** Gestão de usuários e sugestões
- **Rotas de Troca de Senha:** Obrigatória para novos usuários

**Middlewares aplicados:**
- `auth`: Autenticação obrigatória
- `verified`: Email verificado
- `admin`: Apenas administradores
- `forcar_troca_senha`: Força troca de senha

## 🎨 Frontend Modular

### Estrutura JavaScript
**Localização:** `resources/js/modules/`

**Módulos:**
- **dark-mode.js:** Alternância de tema
- **notifications.js:** Sistema de notificações push
- **advanced-search.js:** Busca avançada com filtros
- **dashboard-analytics.js:** Gráficos e métricas
- **ux-enhancements.js:** Melhorias de experiência

### Build Otimizado
**Configuração:** `vite.config.js`

**Otimizações:**
- **Code Splitting:** Separação em chunks
- **Tree Shaking:** Remoção de código não usado
- **Minificação:** Compressão para produção
- **HMR:** Hot Module Replacement para desenvolvimento

## 🚀 Performance

### Cache Strategy
- **Query Cache:** Cache de consultas frequentes
- **Route Cache:** Cache de rotas em produção
- **View Cache:** Cache de templates compilados

### Database Optimization
- **Eager Loading:** Carregamento antecipado de relacionamentos
- **Scopes:** Consultas reutilizáveis e otimizadas
- **Indexes:** Índices em campos de busca frequente

### Frontend Optimization
- **Lazy Loading:** Carregamento sob demanda
- **Asset Bundling:** Agrupamento de recursos
- **Compression:** Compressão de assets

## 🔒 Segurança

### Validações
- **Form Requests:** Validações centralizadas
- **CSRF Protection:** Proteção contra ataques CSRF
- **Rate Limiting:** Limitação de requisições
- **Input Sanitization:** Sanitização de entradas

### Autorização
- **Middleware Admin:** Verificação de permissões
- **Model Policies:** Políticas de acesso a recursos
- **Route Protection:** Proteção de rotas sensíveis

## 🧪 Testing

### Estrutura de Testes
```
tests/
├── Feature/           # Testes de funcionalidade
├── Unit/             # Testes unitários
└── Browser/          # Testes de navegador (Dusk)
```

### Comandos Úteis
```bash
# Executar todos os testes
php artisan test

# Testes com cobertura
php artisan test --coverage

# Testes específicos
php artisan test --filter ViagemTest
```

## 📦 Deployment

### Checklist de Deploy
- [ ] Backup do banco de dados
- [ ] Atualizar dependências: `composer install --no-dev`
- [ ] Executar migrations: `php artisan migrate --force`
- [ ] Compilar assets: `npm run build`
- [ ] Limpar caches: `php artisan optimize:clear`
- [ ] Criar caches: `php artisan optimize`
- [ ] Verificar permissões: `chmod -R 775 storage bootstrap/cache`

### Comandos de Produção
```bash
# Otimização completa
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Limpeza de desenvolvimento
php artisan optimize:clear
```

## 🔧 Comandos Artisan Customizados

### Backup
```bash
php artisan backup:run
```

### Limpeza de Logs
```bash
php artisan logs:clear
```

### Promoção de Usuário
```bash
php artisan user:promote {email}
```

## 📊 Monitoramento

### Logs Importantes
- **Laravel:** `storage/logs/laravel.log`
- **Queries:** Log de consultas lentas
- **Errors:** Erros de aplicação
- **Security:** Tentativas de acesso não autorizado

### Métricas de Performance
- **Response Time:** Tempo de resposta das páginas
- **Database Queries:** Número e tempo de consultas
- **Memory Usage:** Uso de memória
- **Cache Hit Rate:** Taxa de acerto do cache

## 🛠️ Desenvolvimento

### Ambiente Local
```bash
# Iniciar servidor de desenvolvimento
php artisan serve

# Compilar assets em modo watch
npm run dev

# Executar queue workers
php artisan queue:work
```

### Debugging
```bash
# Habilitar debug
APP_DEBUG=true

# Logs detalhados
LOG_LEVEL=debug

# Query logging
DB_LOG_QUERIES=true
```

## 📋 Convenções de Código

### Nomenclatura
- **Classes:** PascalCase (`ViagemController`)
- **Métodos:** camelCase (`createUser`)
- **Variáveis:** camelCase (`$userData`)
- **Constantes:** UPPER_CASE (`MAX_UPLOAD_SIZE`)

### Estrutura de Commits
```
tipo(escopo): descrição

feat(viagem): adicionar validação de KM
fix(auth): corrigir redirecionamento após login
docs(readme): atualizar instruções de instalação
refactor(service): extrair lógica para ViagemService
```

## 🔄 Atualizações Futuras

### Roadmap Técnico
- [ ] Implementar testes automatizados completos
- [ ] Adicionar CI/CD pipeline
- [ ] Implementar cache Redis
- [ ] Adicionar monitoramento com Telescope
- [ ] Implementar API REST completa
- [ ] Adicionar WebSockets para real-time

### Melhorias de Performance
- [ ] Implementar lazy loading de imagens
- [ ] Adicionar service worker para cache offline
- [ ] Otimizar consultas N+1
- [ ] Implementar pagination infinita

---

**Última atualização:** {{ date('d/m/Y H:i') }}
**Versão do Laravel:** 9.x
**Versão do PHP:** 8.0+
