#!/bin/bash
set -e

# Attendre que la base de données soit prête
#echo "⏳ Waiting for the database to be ready..."
#until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
#  >&2 echo "🛑 Database is not ready - sleeping"
#  sleep 2
#done
#
#echo "✅ Database is ready!"
#
## Appliquer les migrations Doctrine
#if ls migrations/*.php 1> /dev/null 2>&1; then
#    echo "📦 Running Doctrine migrations..."
#    php bin/console doctrine:migrations:migrate --no-interaction
#else
#    echo "⚠️  No migrations found. Skipping migration step."
#fi

# Lancer PHP-FPM (ou tout autre commande de fin)
echo "🚀 Starting PHP-FPM..."
exec php-fpm
