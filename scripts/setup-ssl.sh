#!/bin/bash

# Script pour configurer SSL avec Let's Encrypt
# Usage: ./scripts/setup-ssl.sh votre-domaine.com

DOMAIN=$1

if [ -z "$DOMAIN" ]; then
    echo "Usage: ./setup-ssl.sh votre-domaine.com"
    exit 1
fi

echo "🔒 Configuration SSL pour $DOMAIN..."

# Obtenir le certificat
docker compose -f docker-compose.prod.yml run --rm certbot \
    certonly --webroot \
    --webroot-path=/var/www/certbot \
    --email admin@$DOMAIN \
    --agree-tos \
    --no-eff-email \
    -d $DOMAIN

echo "✅ Certificat SSL obtenu !"
echo "📝 Mettez à jour nginx/conf.d/app.conf avec la configuration SSL"