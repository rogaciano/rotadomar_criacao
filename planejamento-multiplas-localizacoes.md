# Planejamento: Múltiplas Localizações de Visualização

Este documento detalha o plano para permitir que usuários com uma localização fixa possam visualizar outras localizações sem poder editá-las.

## 1. Banco de Dados
*   **Migration:** Criar a tabela pivot `user_localizacao_visualizacao`.
    *   `id`
    *   `user_id` (unsignedBigInteger, FK)
    *   `localizacao_id` (unsignedBigInteger, FK)
    *   `timestamps`

## 2. Modelo de Dados (App\Models\User.php)
*   **Relacionamento:** `visualizacoes()` -> `belongsToMany(Localizacao::class, 'user_localizacao_visualizacao')`.
*   **Métodos Auxiliares:**
    *   `isUsuarioLocalizacao()`: Verifica se o usuário tem um `localizacao_id` definido.
    *   `podeGerenciarEtapa($localizacao_id)`: Permite se for Admin OU se a localização for a sua principal.
    *   `getLocalizacoesPermitidasIds()`: Retorna array com ID principal + IDs de visualização.

## 3. Lógica de Negócio e Permissões
*   **Restrição de Escrita:** Usuários que possuem `localizacao_id` (Facções/Setores) não podem Criar, Editar ou Excluir registros de `ProdutoLocalizacao` ou `Movimentacao`.
*   **Gestão de Etapas:** Usuários de localização podem APENAS avançar/voltar etapas na sua localização principal. Nas localizações de "visualização", o botão de etapa deve sumir.

## 4. Controladores (Controllers)
*   **Filtros:** Atualizar `index`, `minhasMovimentacoes` e `Kanban` para usar `whereIn('localizacao_id', $user->getLocalizacoesPermitidasIds())`.
*   **Validação:** Reforçar no `ProdutoLocalizacaoController` que as ações de store/update/destroy são apenas para usuários de backoffice/admin.

## 5. Interface (Views)
*   **Botões de Ação:** 
    *   Ocultar botões de Editar/Excluir para `isUsuarioLocalizacao()`.
    *   Ocultar botões de Etapa se a localização do item não for a principal do usuário.
*   **Filtros de Tela:** Adicionar seletor para o usuário alternar entre as localizações que ele tem direito de ver.

## 6. Administração
*   **UserController:** Atualizar `create` e `edit` para permitir associar as localizações de visualização.
*   **Views de Usuário:** Incluir campo de seleção múltipla (ou checkboxes) para as localizações secundárias.

---
**Arquivo criado em:** 20/01/2026
**Status:** Aguardando início da execução.
