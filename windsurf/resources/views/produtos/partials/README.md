# Partials de Produtos

Organização dos componentes da view de produtos para melhor manutenibilidade.

## 📁 Estrutura

```
partials/
├── _filters.blade.php              # Seção completa de filtros
├── _filter-form.blade.php          # Formulário de filtros (pendente criação)
├── _mobile-cards.blade.php         # Cards para visualização mobile
├── _mobile-card-info.blade.php     # Informações do produto no card
├── _mobile-card-actions.blade.php  # Botões de ação do card
├── _desktop-table.blade.php        # Tabela desktop (pendente criação)
└── _scripts.blade.php              # JavaScript da página (pendente criação)
```

## 🔄 Uso

No `index.blade.php`:

```blade
@include('produtos.partials._filters', [...])
@include('produtos.partials._mobile-cards', ['produtos' => $produtos])
@include('produtos.partials._desktop-table', ['produtos' => $produtos])
@include('produtos.partials._scripts')
```

## ✨ Benefícios

- **Organização**: Cada componente em seu arquivo
- **Reutilização**: Partials podem ser usados em outras views
- **Manutenção**: Mais fácil encontrar e editar código específico
- **Performance**: Laravel faz cache dos templates compilados
- **Colaboração**: Evita conflitos em Git

## 📝 Convenções

- Prefixo `_` para partials privados
- Nome descritivo do componente
- Passar dados via array no `@include`
- Documentar parâmetros necessários em comentários
