# Wind Surf Tool Management System - Setup Guide

## Pré-requisitos

Para executar este projeto Laravel, você precisará instalar os seguintes componentes:

1. **PHP 8.1+**
2. **Composer** (Gerenciador de dependências PHP)
3. **MySQL** (ou MariaDB)
4. **Node.js e NPM** (para gerenciar dependências front-end)
5. **Git** (opcional, mas recomendado para controle de versão)

## Guia de Instalação para Windows

### 1. Instalação do PHP

1. Baixe o PHP para Windows no site oficial: https://windows.php.net/download/
   - Escolha a versão PHP 8.1+ (VS16 x64 Thread Safe)
   - Baixe o arquivo ZIP

2. Extraia o arquivo ZIP para `C:\php`

3. Renomeie o arquivo `php.ini-development` para `php.ini`

4. Edite o arquivo `php.ini` e faça as seguintes alterações:
   - Descomente `extension_dir = "ext"` (remova o `;` do início da linha)
   - Descomente as seguintes extensões (remova o `;`):
     - `extension=curl`
     - `extension=fileinfo`
     - `extension=mbstring`
     - `extension=mysqli`
     - `extension=openssl`
     - `extension=pdo_mysql`
     - `extension=pdo_sqlite`
     - `extension=sqlite3`
     - `extension=zip`
   - Descomente e defina `memory_limit = 512M`
   - Descomente e defina `upload_max_filesize = 10M`
   - Descomente e defina `post_max_size = 10M`

5. Adicione o PHP ao PATH do sistema:
   - Abra o Painel de Controle > Sistema > Configurações avançadas do sistema
   - Clique em "Variáveis de Ambiente"
   - Em "Variáveis do Sistema", encontre a variável "Path" e clique em "Editar"
   - Clique em "Novo" e adicione `C:\php`
   - Clique em "OK" para fechar todas as janelas

6. Verifique a instalação abrindo o Prompt de Comando e digitando:
   ```
   php -v
   ```

### 2. Instalação do Composer

1. Baixe o instalador do Composer em: https://getcomposer.org/Composer-Setup.exe
2. Execute o instalador e siga as instruções
3. Certifique-se de que o instalador detecte corretamente o PHP
4. Verifique a instalação abrindo o Prompt de Comando e digitando:
   ```
   composer --version
   ```

### 3. Instalação do MySQL

1. Baixe o MySQL Installer em: https://dev.mysql.com/downloads/installer/
2. Execute o instalador e escolha a opção "Custom"
3. Selecione os seguintes componentes:
   - MySQL Server
   - MySQL Workbench
   - Connector/J
   - Connector/ODBC
   - Connector/NET
4. Siga as instruções do instalador
5. Configure uma senha forte para o usuário root
6. Complete a instalação e inicie o MySQL Server

### 4. Instalação do Node.js e NPM

1. Baixe o instalador do Node.js em: https://nodejs.org/
2. Escolha a versão LTS (Long Term Support)
3. Execute o instalador e siga as instruções
4. Verifique a instalação abrindo o Prompt de Comando e digitando:
   ```
   node -v
   npm -v
   ```

## Configuração do Projeto Laravel

### 1. Criação do Projeto

1. Abra o Prompt de Comando como administrador
2. Navegue até o diretório onde deseja criar o projeto:
   ```
   cd c:\projetos\rotadoamar
   ```
3. Crie um novo projeto Laravel:
   ```
   composer create-project laravel/laravel windsurf
   ```
4. Entre no diretório do projeto:
   ```
   cd windsurf
   ```

### 2. Configuração do Banco de Dados

1. Crie um novo banco de dados MySQL:
   - Abra o MySQL Workbench
   - Conecte-se ao servidor MySQL local
   - Execute o seguinte comando SQL:
     ```sql
     CREATE DATABASE windsurf CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```

2. Configure o arquivo `.env` no diretório raiz do projeto:
   - Abra o arquivo `.env` em um editor de texto
   - Altere as seguintes linhas:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=windsurf
     DB_USERNAME=root
     DB_PASSWORD=sua_senha_mysql
     ```

### 3. Instalação de Dependências

1. Instale as dependências do PHP:
   ```
   composer install
   ```

2. Instale o pacote Laravel Breeze para autenticação:
   ```
   composer require laravel/breeze --dev
   ```

3. Instale o Tailwind CSS e Alpine.js via Laravel Breeze:
   ```
   php artisan breeze:install blade
   ```

4. Instale as dependências do Node.js:
   ```
   npm install
   ```

5. Compile os assets:
   ```
   npm run dev
   ```

6. Instale pacotes adicionais:
   ```
   composer require spatie/laravel-permission
   composer require maatwebsite/excel
   composer require barryvdh/laravel-dompdf
   composer require livewire/livewire
   ```

### 4. Configuração Inicial do Projeto

1. Gere a chave de aplicação:
   ```
   php artisan key:generate
   ```

2. Execute as migrações para criar as tabelas do sistema:
   ```
   php artisan migrate
   ```

3. Publique os assets dos pacotes instalados:
   ```
   php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
   php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
   ```

4. Crie os seeders para dados iniciais:
   ```
   php artisan make:seeder UserSeeder
   php artisan make:seeder TipoSeeder
   php artisan make:seeder SituacaoSeeder
   php artisan make:seeder StatusSeeder
   ```

5. Execute os seeders:
   ```
   php artisan db:seed
   ```

### 5. Iniciar o Servidor de Desenvolvimento

1. Inicie o servidor Laravel:
   ```
   php artisan serve
   ```

2. Em outro terminal, inicie o compilador de assets:
   ```
   npm run dev
   ```

3. Acesse o projeto em seu navegador:
   ```
   http://localhost:8000
   ```

## Próximos Passos

Após a instalação bem-sucedida, você deve:

1. Criar os modelos, migrações e controladores para todas as entidades do sistema
2. Implementar os relacionamentos entre os modelos
3. Desenvolver as interfaces de usuário com Tailwind CSS e Alpine.js
4. Configurar as permissões de usuário
5. Implementar o dashboard com gráficos e estatísticas
6. Configurar os relatórios e exportações

## Solução de Problemas

### Problemas comuns:

1. **Erro de conexão com o banco de dados**
   - Verifique se o servidor MySQL está em execução
   - Confirme se as credenciais no arquivo `.env` estão corretas
   - Verifique se o banco de dados foi criado

2. **Erro ao executar migrações**
   - Verifique se as extensões PHP necessárias estão habilitadas
   - Verifique se o usuário MySQL tem permissões suficientes

3. **Erro ao compilar assets**
   - Verifique se o Node.js e NPM estão instalados corretamente
   - Tente limpar o cache com `npm cache clean --force`
   - Reinstale as dependências com `npm install`

4. **Erro "Class not found"**
   - Execute `composer dump-autoload`
   - Verifique se todas as dependências foram instaladas

5. **Permissões de arquivo**
   - Certifique-se de que as pastas `storage` e `bootstrap/cache` têm permissões de escrita

## Recursos Adicionais

- Documentação do Laravel: https://laravel.com/docs
- Documentação do Tailwind CSS: https://tailwindcss.com/docs
- Documentação do Alpine.js: https://alpinejs.dev/
- Documentação do Laravel Livewire: https://laravel-livewire.com/docs
