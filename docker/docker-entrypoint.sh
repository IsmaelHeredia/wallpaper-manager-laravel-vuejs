#!/bin/bash

APP_DIR=/var/www/html

echo "[ENTRYPOINT] Iniciando entrypoint ..."

echo "[INFO] Corrigiendo permisos de storage y bootstrap/cache"
chown -R www-data:www-data "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"
chmod -R ug+rwX "$APP_DIR/storage" "$APP_DIR/bootstrap/cache"

if [ ! -d "$APP_DIR/vendor" ]; then
  echo "[INFO] Ejecutando composer install"
  composer install
fi

if ! grep -q "APP_KEY=base64" "$APP_DIR/.env"; then
  echo "[INFO] Generando clave de aplicación"
  php artisan key:generate
fi

if [ ! -f "$APP_DIR/storage/oauth-private.key" ]; then
  echo "[INFO] Generando claves de Passport"
  php artisan passport:keys --force
fi

if [ ! -L "$APP_DIR/public/storage" ]; then
  echo "[INFO] Creando symlink de storage"
  php artisan storage:link
fi

echo "[ENTRYPOINT] Lanzando Apache ..."
exec apache2-foreground
