# 🚗 Sistema Diário de Bordo

Sistema web para gerenciamento e controle de viagens corporativas desenvolvido em Laravel.

## 📋 Sobre o Sistema

O **Diário de Bordo** é uma aplicação completa para controle de viagens corporativas que permite:

- ✅ **Registro de viagens** com controle de quilometragem
- ✅ **Dashboard interativo** com estatísticas e gráficos
- ✅ **Sistema de usuários** com níveis de permissão
- ✅ **Relatórios** em PDF e Excel
- ✅ **Interface responsiva** para desktop e mobile
- ✅ **Validações robustas** para integridade dos dados

## 🛠️ Tecnologias

- **Laravel 9.x** - Framework PHP
- **PHP 8.0+** - Linguagem de programação
- **MySQL** - Banco de dados
- **Bootstrap 5.3** - Frontend responsivo
- **Chart.js** - Gráficos e estatísticas

## 🚀 Instalação Rápida

```bash
# Clone o repositório
git clone [url-do-repositorio]
cd diario-bordo

# Instale as dependências
composer install
npm install

# Configure o ambiente
cp .env.example .env
php artisan key:generate

# Configure o banco no .env e execute:
php artisan migrate
php artisan db:seed

# Compile os assets e inicie o servidor
npm run build
php artisan serve
```

## 📖 Funcionalidades Principais

### Para Usuários
- **Dashboard** com estatísticas pessoais
- **Cadastro de viagens** com validações
- **Visualização** em cards coloridos por status
- **Filtros avançados** por data, destino, condutor
- **Relatórios** personalizáveis

### Para Administradores
- **Gerenciamento de usuários** completo
- **Acesso a todas as viagens** do sistema
- **Relatórios globais** com filtros avançados
- **Sistema de mensagens** e sugestões

## 🎨 Interface

O sistema possui interface moderna e responsiva com:

- **Cards coloridos** indicando status das viagens:
  - 🟢 **Verde**: Concluída
  - 🟡 **Amarelo**: Em andamento  
  - 🔵 **Azul**: Agendada
- **Modal de boas-vindas** personalizável
- **Gráficos interativos** de evolução
- **Layout adaptativo** para todos os dispositivos

## 🔒 Segurança

- **Autenticação** com verificação de email
- **Rate limiting** para proteção contra ataques
- **Validações** robustas de dados
- **CSRF protection** em formulários
- **Middleware** personalizado para controle de acesso

## 📊 Relatórios

O sistema oferece relatórios completos em:

- **PDF** - Para impressão e arquivamento
- **Excel** - Para análise de dados
- **Filtros** por período, usuário, veículo
- **Dados consolidados** de quilometragem

## 🌐 Acesso via Rede Local

Para acesso via IP na rede local, configure no `.env`:

```env
SESSION_DOMAIN=null
SANCTUM_STATEFUL_DOMAINS=192.168.1.100:8000,localhost:8000
```

E inicie o servidor:

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

## 📚 Documentação

Para documentação completa, consulte o arquivo [`DOCUMENTACAO.md`](DOCUMENTACAO.md) que inclui:

- Guia de instalação detalhado
- Manual do usuário
- Guia do administrador
- Troubleshooting
- API e endpoints
- Comandos Artisan

## 🆘 Suporte

Em caso de problemas:

1. Consulte a [documentação completa](DOCUMENTACAO.md)
2. Verifique os logs em `storage/logs/laravel.log`
3. Execute comandos de limpeza de cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## 📝 Licença

Sistema proprietário desenvolvido por **ELN - Soluções**.

---

**Versão**: 1.0.0 | **Laravel**: 9.x | **PHP**: 8.0+
