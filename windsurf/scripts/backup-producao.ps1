[CmdletBinding()]
param(
    [string]$SshHost,
    [string]$SshUser,
    [int]$SshPort,
    [string]$RemoteProject,
    [string]$OutputDirectory
)

$ErrorActionPreference = 'Stop'
$scriptDirectory = Split-Path -Parent $PSCommandPath
$projectDirectory = Split-Path -Parent $scriptDirectory
$localConfig = Join-Path $scriptDirectory 'db-sync.config.local.ps1'

if (Test-Path $localConfig) {
    . $localConfig
}

if (-not $SshHost) { $SshHost = $DbSyncSshHost }
if (-not $SshUser) { $SshUser = $DbSyncSshUser }
if (-not $SshPort) { $SshPort = if ($DbSyncSshPort) { $DbSyncSshPort } else { 52222 } }
if (-not $RemoteProject) { $RemoteProject = $DbSyncRemoteProject }
if (-not $OutputDirectory) { $OutputDirectory = Join-Path $projectDirectory 'backup\database' }

if (-not $SshHost -or -not $SshUser -or -not $SshPort -or -not $RemoteProject) {
    throw 'Configure scripts/db-sync.config.local.ps1 ou informe -SshHost, -SshUser, -SshPort e -RemoteProject.'
}

if (-not (Get-Command ssh -ErrorAction SilentlyContinue)) {
    throw 'O cliente OpenSSH (ssh) não está disponível neste computador.'
}

New-Item -ItemType Directory -Force -Path $OutputDirectory | Out-Null
$timestamp = Get-Date -Format 'yyyy-MM-dd_HH-mm-ss'
$outputFile = Join-Path $OutputDirectory "rotadomar_producao_$timestamp.sql.gz"
if ($RemoteProject.Contains("'")) {
    throw 'O caminho remoto não pode conter aspas simples.'
}

# The server .env remains the only source of production database credentials.
$remoteCommand = @'
cd '{0}' && set -a && . ./.env && set +a && command -v mysqldump >/dev/null && export MYSQL_PWD="$DB_PASSWORD" && mysqldump --single-transaction --quick --routines --events --host="$DB_HOST" --port="$DB_PORT" --user="$DB_USERNAME" "$DB_DATABASE" | gzip -c
'@ -f $RemoteProject

Write-Host "Gerando backup remoto em $outputFile"
& ssh -p $SshPort "$SshUser@$SshHost" $remoteCommand > $outputFile

if ($LASTEXITCODE -ne 0 -or (Get-Item $outputFile).Length -eq 0) {
    throw 'O backup falhou. O arquivo gerado foi mantido para inspeção.'
}

Write-Host "Backup concluído: $outputFile"
