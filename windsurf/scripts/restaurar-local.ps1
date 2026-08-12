[CmdletBinding()]
param(
    [Parameter(Mandatory = $true)]
    [string]$Arquivo,
    [string]$Banco,
    [switch]$Confirmar
)

$ErrorActionPreference = 'Stop'
$scriptDirectory = Split-Path -Parent $PSCommandPath
$projectDirectory = Split-Path -Parent $scriptDirectory
$localConfig = Join-Path $scriptDirectory 'db-sync.config.local.ps1'

if (Test-Path $localConfig) {
    . $localConfig
}

if (-not $Banco) { $Banco = $DbSyncLocalDatabase }
if (-not $Banco) { $Banco = 'rotadomar_backup' }

if (-not $Confirmar) {
    throw "A restauração substituirá as tabelas de '$Banco'. Execute novamente com -Confirmar."
}

$Arquivo = (Resolve-Path $Arquivo).Path
if (-not (Test-Path $Arquivo)) {
    throw "Arquivo não encontrado: $Arquivo"
}

if (-not (Get-Command mysql -ErrorAction SilentlyContinue)) {
    throw 'O cliente mysql não está disponível no PATH deste computador.'
}

function Get-EnvValue([string]$Path, [string]$Name) {
    $line = Get-Content $Path | Where-Object { $_ -match "^$([regex]::Escape($Name))=" } | Select-Object -First 1
    if (-not $line) { return $null }

    $value = $line.Substring($Name.Length + 1).Trim()
    if ($value.Length -ge 2 -and (($value[0] -eq '"' -and $value[$value.Length - 1] -eq '"') -or ($value[0] -eq "'" -and $value[$value.Length - 1] -eq "'"))) {
        return $value.Substring(1, $value.Length - 2)
    }

    return $value
}

$envFile = Join-Path $projectDirectory '.env'
$dbHost = Get-EnvValue $envFile 'DB_HOST'
$dbPort = Get-EnvValue $envFile 'DB_PORT'
$dbUser = Get-EnvValue $envFile 'DB_USERNAME'
$dbPassword = Get-EnvValue $envFile 'DB_PASSWORD'

if (-not $dbHost -or -not $dbUser) {
    throw 'DB_HOST e DB_USERNAME devem estar definidos no .env local.'
}
if (-not $dbPort) { $dbPort = '3306' }

$sqlFile = $Arquivo
$temporaryFile = $null

try {
    if ($Arquivo.EndsWith('.gz', [System.StringComparison]::OrdinalIgnoreCase)) {
        $temporaryFile = Join-Path $env:TEMP ("rotadomar_restore_{0}.sql" -f [guid]::NewGuid())
        $input = [System.IO.File]::OpenRead($Arquivo)
        $gzip = New-Object System.IO.Compression.GzipStream($input, [System.IO.Compression.CompressionMode]::Decompress)
        $output = [System.IO.File]::Create($temporaryFile)
        try { $gzip.CopyTo($output) } finally { $output.Dispose(); $gzip.Dispose(); $input.Dispose() }
        $sqlFile = $temporaryFile
    }

    $env:MYSQL_PWD = $dbPassword
    & mysql --host=$dbHost --port=$dbPort --user=$dbUser --execute="CREATE DATABASE IF NOT EXISTS ``$Banco`` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    if ($LASTEXITCODE -ne 0) { throw 'Não foi possível criar ou acessar o banco local.' }

    Write-Host "Restaurando $Arquivo em $Banco"
    & mysql --host=$dbHost --port=$dbPort --user=$dbUser --database=$Banco --execute="SOURCE $($sqlFile.Replace('\', '/'));"
    if ($LASTEXITCODE -ne 0) { throw 'A restauração falhou.' }

    Write-Host "Restauração concluída no banco: $Banco"
}
finally {
    Remove-Item Env:MYSQL_PWD -ErrorAction SilentlyContinue
    if ($temporaryFile -and (Test-Path $temporaryFile)) {
        Remove-Item -LiteralPath $temporaryFile -Force
    }
}
