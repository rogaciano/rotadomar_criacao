#!/bin/bash

# Script para limpar o cache do Laravel no servidor

echo "Limpando o cache do Laravel..."

# Limpar o cache de configuração
php artisan config:clear
echo "✓ Cache de configuração limpo"

# Limpar o cache de rotas
php artisan route:clear
echo "✓ Cache de rotas limpo"

# Limpar o cache de views
php artisan view:clear
echo "✓ Cache de views limpo"

# Limpar o cache de aplicação
php artisan cache:clear
echo "✓ Cache de aplicação limpo"

# Limpar o cache de compilação
php artisan optimize:clear
echo "✓ Cache de compilação limpo"

echo "Todos os caches foram limpos com sucesso!"
echo "Se o problema persistir, pode ser necessário reiniciar o servidor web (Apache/Nginx) ou PHP-FPM."
