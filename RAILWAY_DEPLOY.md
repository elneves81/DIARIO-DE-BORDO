# 🚂 Railway Deploy Instructions

## ✅ Arquivos de Configuração

Esta aplicação usa **nixpacks** (não Docker) para deploy no Railway:

- `nixpacks.toml` - Configuração de build 
- `start.sh` - Script de inicialização
- `railway.toml` - Configurações Railway
- `.railwayignore` - Arquivos ignorados

## 🚫 Dockerfile Removido

O **Dockerfile foi removido** porque causava conflito:
- Railway preferia Docker over nixpacks
- `php artisan key:generate` falhava no build
- Agora usa nixpacks + script personalizado

## 📋 Railway Setup

1. **Build Process (nixpacks)**:
   - Instala PHP/Node dependencies
   - Executa `npm run build`
   - **NÃO** executa comandos Laravel

2. **Runtime Process (start.sh)**:
   - Cria `.env` do `.env.example`
   - Gera `APP_KEY`
   - Executa migrations
   - Inicia servidor

## ⚙️ Variables Needed

Configure no Railway Dashboard → Variables:

```env
DATABASE_URL=postgresql://... (automático)
APP_URL=https://seu-app.railway.app
MAIL_*=configurar conforme necessário
```

## 🎯 Expected Behavior

- ✅ Build sem Docker
- ✅ Runtime com Laravel setup
- ✅ APP_KEY gerada automaticamente
- ✅ Migrations executadas

---

**Se houver problemas, verifique se Railway não está tentando usar Docker**
