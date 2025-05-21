# Wallpaper Manager

Este proyecto es un sistema de gestión de wallpapers desarrollado con **Laravel** en el backend y **VueJS** (integrado con **Vuetify**) en el frontend. Utiliza una base de datos **MySQL**.

## Funcionalidades principales

- Autenticación obligatoria mediante **JWT**.
- Posibilidad de cambiar usuario y contraseña.
- Cambio dinámico de tema (modo claro/oscuro).
- Gestión completa de wallpapers: agregar, editar y eliminar.
- Organización de wallpapers por **horarios** y **estaciones del año**.
- Búsqueda de wallpapers por nombre, horarios y estaciones desde la página principal.
  - Se utiliza **Pinia** para conservar los filtros de búsqueda mientras se navega por la aplicación.
- Visualización de **gráficos estadísticos** que muestran la cantidad de wallpapers por horario y por estación.

## Capturas de pantalla

A continuación, se muestran algunas imágenes del sistema en funcionamiento:

![screenshot]()

---

## Instalación del proyecto

### Backend (Laravel)

1. Renombrar el archivo `.env.example` a `.env`.
2. Configurar la conexión a la base de datos MySQL en el archivo `.env`.
3. Ejecutar los siguientes comandos:

```
composer install
```

```
php artisan key:generate
```

```
php artisan migrate
```

```
php artisan passport:keys --force
```

```
php artisan passport:client --password
```

> ⚠️ Al ejecutar el comando anterior, se te proporcionará el `client id` y `client secret`. Copia estos valores y colócalos en el archivo `.env` en las variables `PASSPORT_CLIENT_ID` y `PASSPORT_CLIENT_SECRET`.

```
php artisan db:seed --class=DatabaseSeeder
```

```
php artisan storage:link
```

---

### Frontend (VueJS)

Para instalar las dependencias del frontend, ejecuta:

```
npm install
```

---

### Iniciar la aplicación

Para levantar la aplicación en modo desarrollo, ejecuta:

```
php artisan serve --port=9090
```

```
npm run dev
```

---

## Pruebas unitarias

Este proyecto incluye pruebas unitarias para verificar el correcto funcionamiento de los endpoints de Laravel.

1. Renombrar el archivo `.env.testing.example` a `.env.testing`.
2. Configurar la conexión a la base de datos de pruebas y generar un `APP_KEY` con el siguiente comando:

```
php -r "echo 'base64:'.base64_encode(random_bytes(32)).\"\n\";"
```

3. Ejecutar las pruebas:

```
php artisan test
```

Deberías recibir un resultado similar a:

```
  PASS  Tests\Feature\ApiEndpointsTest
✓ el usuario puede iniciar sesion                                                                                                              1.60s  
✓ el usuario autenticado puede validarse                                                                                                       0.10s  
✓ el usuario puede actualizar su perfil                                                                                                        0.11s  
✓ se pueden listar las estaciones                                                                                                              0.10s  
✓ se puede cargar una estacion                                                                                                                 0.09s  
✓ se pueden listar los horarios                                                                                                                0.10s  
✓ se puede cargar un horario                                                                                                                   0.09s  
✓ se pueden listar los wallpapers                                                                                                              0.12s  
✓ se puede cargar un wallpaper                                                                                                                 0.10s  
✓ se puede crear un wallpaper                                                                                                                  0.13s  
✓ se puede actualizar un wallpaper                                                                                                             0.11s  
✓ se puede eliminar un wallpaper                                                                                                               0.11s  
✓ las estadisticas devuelve datos correctos                                                                                                        0.10s  

Tests:    13 passed (84 assertions)
Duration: 2.94s
```

---

## Uso con Docker

1. Renombrar el archivo `.env.docker.example` a `.env.docker` que esta en la carpeta docker.
2. Configurar los datos de conexión a la base de datos (excepto `DB_HOST`, que ya viene predefinido).
3. Ejecutar los siguientes comandos para la instalación y configuración en entorno Docker:

```
docker compose up -d --build
```

4. Acceder al contenedor para ejecutar los comandos de configuración:

```
docker exec -it wallpapers_laravel bash
```

5. Ejecutar las migraciones y sembrado de la base de datos:

```
php artisan migrate --seed
```

6. Crear el cliente de Passport para password grant:

```
php artisan passport:client --password
```

> ⚠️ Al igual que en la instalación normal, copia el `client id` y `client secret` generados y colócalos en las variables `PASSPORT_CLIENT_ID` y `PASSPORT_CLIENT_SECRET` del archivo `docker/.env.docker`.

## Script de automatización (Go)

El proyecto incluye un script escrito en **Go**, ubicado en la carpeta `scripts`, que permite automatizar la selección y cambio del wallpaper en función de la **hora del día** y la **estación del año**.

### ¿Qué hace este script?

- Realiza login en el sistema usando la API (`/api/v1/ingreso`).
- Descarga la lista de wallpapers disponibles desde `/api/v1/wallpapers`.
- Filtra los wallpapers según:
  - La estación actual (verano, otoño, invierno, primavera).
  - El momento del día (mañana, tarde, noche, madrugada).
- Selecciona uno al azar entre los que cumplen con ese criterio.
- Lo descarga a la carpeta `downloads/` y lo establece como fondo de pantalla.
  - Funciona tanto en **Linux** (GNOME) como en **Windows**.
- Repite el proceso cada 1 hora (en el código actual: cada 20 segundos para pruebas).

### Ejecución

Para ejecutar el script:

```
cd scripts
go run wallpaper.go
```

---
