# 🚛 Motorista PWA - Rota do Mar

App PWA para motoristas acompanharem e gerenciarem coletas logísticas.

## Estrutura

```
motorista-app/
├── index.html          ← Tela de login
├── app.html            ← Dashboard de coletas
├── manifest.json       ← PWA manifest
├── sw.js               ← Service Worker (cache + push)
├── server.js           ← Dev server (Node.js)
├── css/app.css         ← Estilos extras
├── js/
│   ├── api.js          ← Módulo de comunicação com API
│   ├── app.js          ← (reservado para lógica futura)
│   └── push.js         ← Registro de push notifications
└── icons/
    └── icon-192.svg    ← Ícone placeholder (substituir por PNG)
```

## Dev local

```bash
cd motorista-app
node server.js
# Acesse http://localhost:3000
```

Na tela de login, clique em "Configurar servidor" e informe a URL do Laravel:
- Local: `http://localhost:8000`
- Produção: `https://seu-dominio.com.br`

## Deploy em produção

### 1. Backend (Laravel)

Executar no servidor:

```sql
-- Rodar o script SQL:
-- windsurf/database/migrations/motorista_pwa_migrations.sql
```

Arquivos alterados no Laravel:
- `composer.json` → adicionado `laravel/sanctum`
- `config/sanctum.php` → configuração Sanctum
- `config/cors.php` → CORS para API
- `bootstrap/app.php` → registro de `routes/api.php`
- `app/Http/Kernel.php` → Sanctum middleware no grupo `api`
- `app/Models/User.php` → trait `HasApiTokens`
- `routes/api.php` → endpoints da API do motorista
- `app/Http/Controllers/Api/MotoristaApiController.php` → controller
- `app/Models/PushSubscription.php` → model
- `database/migrations/2026_03_19_000004_create_push_subscriptions_table.php`

No `.env` do Laravel, adicione o domínio da PWA:
```
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,motorista.seudominio.com.br
```

### 2. Frontend (PWA)

A PWA é composta de arquivos estáticos. Para deploy:

**Opção A - Subpasta do Laravel:**
```bash
cp -r motorista-app/ windsurf/public/motorista/
# Acessar via: https://seudominio.com.br/motorista/
```

**Opção B - Domínio/subdomínio separado:**
- Servir com Nginx/Apache apontando para a pasta `motorista-app/`
- Requer HTTPS para Service Worker funcionar

### 3. Ícones

Substituir `icons/icon-192.svg` por PNGs reais:
- `icons/icon-192.png` (192x192px)
- `icons/icon-512.png` (512x512px)

Use o logo da Rota do Mar com fundo indigo (#4f46e5).

### 4. Push Notifications (opcional)

Para ativar push notifications:

1. Gerar chaves VAPID:
```bash
# No servidor Laravel:
php -r "
\$keys = sodium_crypto_sign_keypair();
echo 'Public: ' . base64_encode(sodium_crypto_sign_publickey(\$keys)) . PHP_EOL;
echo 'Private: ' . base64_encode(sodium_crypto_sign_secretkey(\$keys)) . PHP_EOL;
"
```

2. Adicionar ao `.env`:
```
VAPID_PUBLIC_KEY=sua_chave_publica
VAPID_PRIVATE_KEY=sua_chave_privada
```

3. Na PWA, configurar a chave pública em `js/push.js` ou via localStorage.

## API Endpoints

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| POST | `/api/motorista/login` | Login → token |
| POST | `/api/motorista/logout` | Revogar token |
| GET | `/api/motorista/perfil` | Dados do motorista |
| GET | `/api/motorista/coletas` | Lista coletas (ativas/histórico) |
| GET | `/api/motorista/coletas/{id}` | Detalhe de coleta |
| POST | `/api/motorista/coletas/{id}/confirmar-chegada` | Confirmar chegada na origem |
| POST | `/api/motorista/coletas/{id}/confirmar-entrega` | Confirmar entrega no destino |
| POST | `/api/motorista/push-subscribe` | Registrar push subscription |
| DELETE | `/api/motorista/push-unsubscribe` | Remover push subscription |
