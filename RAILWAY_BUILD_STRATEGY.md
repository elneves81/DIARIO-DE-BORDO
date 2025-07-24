# Configuração Railway - Sem APP_KEY no build

## Estratégia: Gerar APP_KEY apenas no runtime

1. **Remover APP_KEY do build**
2. **Gerar APP_KEY no start do servidor**
3. **Usar variáveis de ambiente do Railway**

## Arquivos Modificados:

### start.sh
- Script robusto de inicialização
- Verifica e cria .env se necessário  
- Gera APP_KEY apenas se não existir
- Executa migrations apenas se DB disponível

### nixpacks.toml
- Build sem comandos Laravel que precisam de .env
- Apenas instala dependências
- APP_KEY gerada no runtime, não no build

### railway.toml
- StartCommand usa script personalizado
- Evita executar artisan commands no build

## Como Testar:

1. **Build deve passar** - sem comandos artisan
2. **Runtime deve funcionar** - script cria tudo necessário
3. **APP_KEY gerada** automaticamente no primeiro start

## Se ainda der erro:

### Opção A: Variáveis Railway
Configure no Railway Dashboard → Variables:
```
APP_KEY=base64:SUA_CHAVE_AQUI
```

### Opção B: Dockerfile customizado
Usar Dockerfile em vez de nixpacks se necessário.

---

**Este approach separa build (deps) de runtime (config)**
