# Instalação do Projeto Grupo Rota do Mar PLM

## 📋 Comandos para Configuração em Novo Computador

### 1. Clonar o repositório
```bash
git clone https://github.com/rogaciano/rotadomarPLM.git
cd rotadomarPLM
```

### 2. Instalar dependências
```bash
composer install
npm install
```

### 3. Configurar ambiente
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar banco de dados
Edite o arquivo `.env` com suas credenciais:
```env
DB_DATABASE=rotadomar
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Executar migrações
```bash
php artisan migrate
```

### 6. Iniciar o servidor
```bash
php artisan serve
```

## 🚀 Comando Resumo (uma linha)
```bash
git clone https://github.com/rogaciano/rotadomarPLM.git && cd rotadomarPLM && composer install && npm install && cp .env.example .env && php artisan key:generate
```
*(Depois editar o .env e executar `php artisan migrate` e `php artisan serve`)*

## 🔧 Pré-requisitos Necessários
- PHP 8.1 ou superior
- Composer
- Node.js/NPM
- MySQL ou MariaDB

## 🌐 Acesso
Após a instalação, acesse o projeto em:
```
http://127.0.0.1:8000
```

## 📝 Observações
- O projeto já está configurado com o .gitignore atualizado
- Arquivos temporários e configurações locais estão protegidos
- Todas as funcionalidades estão disponíveis incluindo:
  - Dashboard de Capacidade
  - Relatórios PDF otimizados
  - Sistema de gestão de produtos
