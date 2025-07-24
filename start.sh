#!/bin/bash

# Script de inicialização para Railway
echo "🚂 Iniciando aplicação Diário de Bordo no Railway..."

# Verificar se arquivo .env existe, se não, criar
if [ ! -f ".env" ]; then
    echo "📄 Arquivo .env não encontrado. Criando a partir do .env.example..."
    cp .env.example .env
    echo "✅ Arquivo .env criado com sucesso"
else
    echo "✅ Arquivo .env já existe"
fi

# Verificar se APP_KEY está definida
if grep -q "APP_KEY=$" .env || ! grep -q "APP_KEY=" .env; then
    echo "🔑 APP_KEY não definida. Gerando nova chave..."
    php artisan key:generate --force
    echo "✅ APP_KEY gerada com sucesso"
else
    echo "✅ APP_KEY já definida"
fi

# Executar migrations se banco estiver disponível
if [ ! -z "$DATABASE_URL" ]; then
    echo "🗄️ Executando migrations..."
    php artisan migrate --force
    echo "✅ Migrations executadas"
else
    echo "⚠️ DATABASE_URL não definida, pulando migrations"
fi

# Limpar e configurar cache
echo "⚡ Configurando cache..."
php artisan config:cache
php artisan route:cache 2>/dev/null || echo "Routes cache skipped"
php artisan view:cache 2>/dev/null || echo "Views cache skipped"

# Criar link de storage
echo "🔗 Configurando storage..."
php artisan storage:link 2>/dev/null || echo "Storage link já existe"

# Iniciar servidor
echo "🚀 Iniciando servidor na porta ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
