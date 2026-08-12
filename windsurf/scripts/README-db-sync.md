# Backup e restauração do banco

## Configuração inicial

No PowerShell, dentro do projeto:

```powershell
Copy-Item scripts\db-sync.config.example.ps1 scripts\db-sync.config.local.ps1
```

Edite apenas o arquivo `db-sync.config.local.ps1`. Ele não é enviado ao Git e não contém senha de banco. As credenciais são lidas do `.env` do servidor e do `.env` local.

Preencha também a porta SSH correta do servidor. No servidor atual, ela é `52222`.

## Baixar backup da produção

```powershell
.\scripts\backup-producao.ps1
```

O arquivo compactado é salvo em `backup\database` e não é versionado.

## Restaurar localmente

```powershell
.\scripts\restaurar-local.ps1 -Arquivo .\backup\database\rotadomar_producao_YYYY-MM-DD_HH-mm-ss.sql.gz -Banco rotadomar_backup -Confirmar
```

A restauração substitui tabelas existentes no banco informado. Nunca execute o script de restauração apontando para o banco de produção.
