# Rota do Mar — Status e Próximos Passos

> **Ultima atualização:** 2026-04-23
> **Branch de trabalho:** `feature/ui-ux-improvements`

---

## 1. O que JÁ FOI FEITO

### Sidebar lateral colapsável (CONCLUÍDA)
- Logo + tema + expandir/recolher no topo
- Usuário (avatar, nome, email, Perfil/Sair) abaixo do logo
- Notificações e Sugestões com badge e dropdown
- Criação com rota real na sidebar (`route('criacao.index')`)

- Grupos colapsáveis: Cadastros, Consultas, Administração
- Estado persistido em localStorage, dark mode via Alpine.store

**Arquivos aplicados no feature/ui-ux-improvements:**
1. windsurf/resources/js/app.js
2. windsurf/resources/views/components/sidebar-item.blade.php
3. windsurf/resources/views/components/sidebar-group.blade.php
4. windsurf/resources/views/layouts/navigation.blade.php
5. windsurf/resources/views/layouts/app.blade.php

---

## 2. O que está PENDENTE

### A. Módulo Criação (EM IMPLEMENTAÇÃO)
Campos: `data_entrada_processo`, `obs_designer`, `direcionamento_comercial_id`, `etapa_producao_id`, `estilista_id`

Decisões tomadas:
- faccao = Localizacao (model existente)
- Visão BEL = visão da Criação, não um papel separado
- DirecionamentoComercial já existe
- EtapaProducao já existe
- URL-base do módulo = `/criacao`
- Ao definir etapa, o produto sai da listagem da Criação e continua visível em `produtos`
- Ao definir etapa, o produto passa para o status `AGUARDANDO DESENVOLVIMENTO` (já existente no banco)
- Vínculo `Estilista ↔ User` é opcional na v1

Ajustes já iniciados no código:
- [x] Rotas do módulo `criacao`
- [x] `CriacaoController` + `CriacaoRequest`
- [x] `ProdutoPolicy@editObsDesigner`
- [x] vínculo opcional `estilistas.user_id`
- [x] sidebar apontando para `criacao.index`
- [x] views iniciais (`index`, `bel`, `kanban`, `create`, `edit`, `_form`)
- [x] `PermissionSeeder` com permissão `criacao`
- [x] seeder admin movido para `.env` com fallback seguro
- [ ] rodar migrations
- [ ] revisar manualmente o fluxo completo no browser
- [ ] decidir se o kanban atual já atende ou se precisa de versão mais rica na onda seguinte

Escopo:
1. Migration + Model + Relationships
2. Controller + Form Request
3. Rotas com middleware de permissão
4. Policy para obs_designer
5. Views (lista, form, visão BEL)
6. Sidebar: trocar href="#" por route('criacao.index')
7. Seeder de permissões
8. Logs via Spatie ActivityLog

### B. Segurança backend (pendente)
- [x] Credenciais hardcoded do admin movidas para `.env`/senha aleatória
- [ ] Revisar bypasses de permissão
- [ ] Rate limiting em rotas de API
- [ ] CSRF em endpoints ajax
- [ ] Validação de upload de arquivos

---

## 3. Como RETOMAR

Abra novo chat e cole:

    Leia windsurf/docs/STATUS_E_PROXIMOS_PASSOS.md e vamos continuar.
    Quero validar o módulo Criação já implementado e seguir para os próximos ajustes.

---

## 4. Contexto técnico

Stack: Laravel 12 / PHP 8.3 / Blade / Alpine.js / Tailwind / Vite / MySQL
App: E:\projetos\RotaDoMar\windsurf\
Dev: npm run dev (terminal 1) + php artisan serve (terminal 2)
URL: http://localhost:8000

Models existentes: Produto, Tecido, Estilista, Marca, GrupoProduto, Tipo,
Status, Situacao, Localizacao, DirecionamentoComercial, EtapaProducao,
Movimentacao, User (hasPermission, canAction, isAdmin)
