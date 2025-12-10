# 📄 Solução: Iframe Redimensionável para Preview de PDF

**Arquivo:** `docs/solucao-iframe-pdf-redimensionavel.md`  
**Data:** 10/12/2025  
**Contexto:** Solução implementada no Kanban (index.blade.php) para exibir PDFs em modal com redimensionamento

---

## 🎯 Problema Original

Tentativas anteriores de criar um iframe para exibir PDFs enfrentavam os seguintes desafios:
- **Iframe não respeitava dimensões** definidas no CSS
- **Conteúdo PDF não se ajustava** corretamente ao tamanho do modal
- **Redimensionamento não funcionava** de forma intuitiva
- **Layout quebrava** ao tentar redimensionar

---

## ✅ Solução Implementada

A solução utiliza uma combinação de técnicas CSS3 e HTML5 modernas para criar um modal redimensionável com iframe funcional.

### 1. Estrutura HTML do Modal

```html
<div id="modal-preview-anexo"
     class="fixed inset-0 z-50 bg-black bg-opacity-60 flex items-center justify-center p-4"
     style="display: none;">
    <div class="bg-white flex flex-col shadow-2xl rounded-lg"
         style="width: 1160px; height: 1640px; max-width: 100%; max-height: 98vh; 
                resize: both; overflow: hidden; min-width: 320px; min-height: 480px;">
        
        <!-- Cabeçalho Fixo -->
        <div class="flex items-center justify-between px-4 py-2 border-b bg-gray-50 flex-shrink-0 cursor-move">
            <h3 id="modal-preview-anexo-titulo" class="text-sm font-semibold text-gray-800">
                Preview do Anexo
            </h3>
            <div class="flex items-center gap-2">
                <button type="button" onclick="resetarTamanhoModal()" 
                        class="text-xs text-blue-600 hover:text-blue-800 mr-2"
                        title="Resetar Tamanho">
                    Resetar
                </button>
                <button type="button" onclick="fecharPreviewAnexo()"
                        class="text-gray-500 hover:text-gray-700 text-2xl leading-none">
                    ✕
                </button>
            </div>
        </div>

        <!-- Corpo com Iframe -->
        <div class="flex-1 w-full h-full relative bg-gray-100 overflow-hidden">
            <iframe id="modal-preview-anexo-frame"
                    src=""
                    class="w-full h-full border-0 absolute inset-0"
                    title="Preview do Anexo PDF">
            </iframe>
        </div>
        
        <!-- Grip de Redimensionamento Visual -->
        <div class="h-3 bg-gray-100 cursor-nwse-resize w-full flex-shrink-0 border-t border-gray-200" 
             title="Arraste para redimensionar"></div>
    </div>
</div>
```

---

## 🔑 Elementos-Chave da Solução

### 1. **Container com `resize: both`**

```css
style="width: 1160px; height: 1640px; max-width: 100%; max-height: 98vh; 
       resize: both; overflow: hidden; min-width: 320px; min-height: 480px;"
```

**Por quê funciona:**
- `resize: both` - Permite redimensionamento manual pelo usuário em ambas as direções
- `overflow: hidden` - **CRÍTICO**: Necessário para que `resize` funcione
- `min-width/min-height` - Define tamanhos mínimos para evitar colapso
- `max-width/max-height` - Garante responsividade em telas menores

### 2. **Flexbox Layout (`flex flex-col`)**

A estrutura usa flexbox para garantir que o iframe ocupe todo o espaço disponível:

```html
<div class="flex flex-col">  <!-- Container principal -->
    <div class="flex-shrink-0">  <!-- Cabeçalho: não encolhe -->
    <div class="flex-1">          <!-- Corpo: cresce e encolhe -->
    <div class="flex-shrink-0">  <!-- Grip: não encolhe -->
</div>
```

**Por quê funciona:**
- `flex-1` no corpo permite que o iframe ocupe todo o espaço restante
- `flex-shrink-0` no cabeçalho e grip mantém tamanhos fixos
- Layout se ajusta automaticamente ao redimensionar

### 3. **Iframe com Posicionamento Absoluto**

```html
<div class="flex-1 w-full h-full relative bg-gray-100 overflow-hidden">
    <iframe class="w-full h-full border-0 absolute inset-0"></iframe>
</div>
```

**Por quê funciona:**
- Container pai com `relative` e `flex-1`
- Iframe com `absolute inset-0` preenche todo o pai
- `w-full h-full` garante 100% do espaço disponível

### 4. **Parâmetro de Zoom no PDF**

```javascript
function abrirPreviewAnexo(url, descricao) {
    // ...
    let pdfUrl = url;
    if (!pdfUrl.includes('#')) {
        pdfUrl += '#view=FitH';  // ← Ajuste de zoom do PDF
    }
    iframe.src = pdfUrl;
    // ...
}
```

**Parâmetros úteis:**
- `#view=FitH` - Ajusta largura da página ao iframe
- `#view=FitV` - Ajusta altura da página ao iframe
- `#zoom=150` - Define zoom específico (150%)
- `#page=2` - Abre em página específica

### 5. **Grip Visual de Redimensionamento**

```html
<div class="h-3 bg-gray-100 cursor-nwse-resize w-full flex-shrink-0 border-t border-gray-200" 
     title="Arraste para redimensionar"></div>
```

**Por quê é importante:**
- Fornece **indicação visual** clara de que o modal é redimensionável
- `cursor-nwse-resize` mostra cursor de redimensionamento
- Facilita a descoberta da funcionalidade pelo usuário

### 6. **Função de Reset do Tamanho**

```javascript
function resetarTamanhoModal() {
    const modalContent = document.querySelector('#modal-preview-anexo > div');
    if (modalContent) {
        modalContent.style.width = '1160px';
        modalContent.style.height = '1640px';
    }
}
```

**Benefícios:**
- Permite voltar ao tamanho padrão facilmente
- Melhora UX quando usuário perde controle do tamanho

---

## 🎨 CSS Completo

```css
/* O modal não precisa de CSS especial, mas é importante:
   1. Não sobrescrever overflow do container
   2. Não definir height/width fixos no iframe pai
   3. Usar flexbox para layout */
```

---

## ⚠️ Armadilhas Comuns (O que NÃO fazer)

### ❌ **Erro 1: Esquecer `overflow: hidden`**
```html
<!-- ERRADO -->
<div style="resize: both;">  <!-- Não funciona sem overflow -->
```

### ❌ **Erro 2: Definir altura fixa no container do iframe**
```html
<!-- ERRADO -->
<div class="flex-1" style="height: 600px;">  <!-- Não redimensiona -->
    <iframe class="w-full h-full"></iframe>
</div>
```

### ❌ **Erro 3: Usar position relative no iframe**
```html
<!-- ERRADO -->
<iframe class="relative w-full h-full"></iframe>  <!-- Não preenche -->
<!-- CORRETO -->
<iframe class="absolute inset-0 w-full h-full"></iframe>
```

### ❌ **Erro 4: Não definir min/max dimensions**
```html
<!-- ERRADO -->
<div style="resize: both; overflow: hidden;">  <!-- Pode colapsar -->
<!-- CORRETO -->
<div style="resize: both; overflow: hidden; min-width: 320px; min-height: 480px;">
```

---

## 📋 Checklist de Implementação

Ao implementar esta solução em outros lugares:

- [ ] Container principal tem `resize: both` e `overflow: hidden`
- [ ] Definir `min-width`, `min-height`, `max-width`, `max-height`
- [ ] Layout usa `flex flex-col` no container
- [ ] Cabeçalho e rodapé com `flex-shrink-0`
- [ ] Corpo do iframe com `flex-1`
- [ ] Container do iframe é `relative`
- [ ] Iframe é `absolute inset-0 w-full h-full`
- [ ] Adicionar parâmetro `#view=FitH` na URL do PDF
- [ ] Incluir grip visual de redimensionamento
- [ ] Implementar função de reset do tamanho

---

## 🔧 Variações e Extensões

### Tamanhos Diferentes
```javascript
// Pequeno (ficha técnica)
style="width: 800px; height: 1000px;"

// Médio (documento A4)
style="width: 1160px; height: 1640px;"  // ← Padrão atual

// Grande (desenho técnico)
style="width: 1400px; height: 1000px;"
```

### Orientação Paisagem
```javascript
// Para PDFs largos
style="width: 1640px; height: 1160px;"
```

### Modo Tela Cheia
```javascript
function expandirTelaCheia() {
    const modal = document.querySelector('#modal-preview-anexo > div');
    modal.style.width = '98vw';
    modal.style.height = '98vh';
}
```

---

## 📚 Referências Técnicas

- [MDN: CSS resize](https://developer.mozilla.org/en-US/docs/Web/CSS/resize)
- [MDN: Flexbox](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Flexible_Box_Layout)
- [PDF Open Parameters](https://www.adobe.com/content/dam/acom/en/devnet/acrobat/pdfs/pdf_open_parameters.pdf)

---

## 🎯 Resultado Final

✅ **Modal redimensionável** com controle intuitivo  
✅ **Iframe responsivo** que se ajusta automaticamente  
✅ **PDF com zoom adequado** para melhor visualização  
✅ **UX aprimorada** com indicadores visuais e função de reset  
✅ **Compatível** com Chrome, Firefox, Edge e Safari  

---

## 🔄 Histórico de Mudanças

| Data | Versão | Mudança |
|------|--------|---------|
| 10/12/2025 | 1.0 | Documentação inicial da solução |

---

**Desenvolvido por:** Equipe Rota do Amar  
**Testado em:** Chrome 120+, Firefox 120+, Edge 120+  
**Status:** ✅ Em produção

---

## 📌 Principais Insights da Solução

1. **`resize: both` + `overflow: hidden`** - Esta combinação é ESSENCIAL e muitas vezes esquecida
2. **Flexbox com `flex-1`** - Permite que o iframe ocupe todo espaço disponível dinamicamente
3. **Iframe com `absolute inset-0`** - Garante preenchimento completo do container pai
4. **Parâmetro `#view=FitH`** - Melhora significativamente a experiência de visualização do PDF
5. **Grip visual** - Aumenta a descoberta da funcionalidade de redimensionamento

Esta solução é robusta, responsiva e oferece excelente UX para visualização de PDFs! 🎉
