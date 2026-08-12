# Copy this file to db-sync.config.local.ps1 and adjust the non-secret values.
# The scripts read database credentials from each environment's .env file.

$DbSyncSshHost = 'sistemasrota.com.br'
$DbSyncSshUser = 'admin'
$DbSyncSshPort = 52222
$DbSyncRemoteProject = '/var/www/html/criacao/windsurf'
$DbSyncLocalDatabase = 'rotadomar_backup'
