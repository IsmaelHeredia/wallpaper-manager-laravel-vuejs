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

![screenshot](https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEg2fH9z7PtMpR8CT1X8_C27UvmyxGjSxSERUJklo39AVcaZc3aoktWzePREsw05ON3UMMuY2qywNt6pZVGLJcyztEKTMCRsdxe3vRXvb48hmvFf_WP-1DmpVNtbI8Dz4sqvvqZy3IpDAnaPSaPJYdycZ-4bJxmroJhZAljfXzOUN8H3uSNza8t4mmSMR7M/s1917/1.png)

![screenshot](https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEieTL6Or8biyT1iAJayYtUViZR1hXcuumkkE1hK-Qtg1x5THNLACTaqW_wGhOzXYf3W1Qtn4dXXP3IQPQ0EAGTVESutSJZEhwkLPWsXg6P0sD3Zm4gpqESR75_1AkxaHL4O8Adn_yEMjPlpJdXiXCWEpkcbd6Z1eKoFnKdhZy3Xu3IuyGUrHe6lT-43z3Q/s1914/2.png)

![screenshot](https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgHqt7xv8WwLlWJHOsfOSAMzDlzBtotKn0nR15pS4pWjElT3mAdJXKjH6dVIzgXDMyPDGXy1q9EvhP8T4tYsqik7V9Z-u2TsswzJKXxpgVvHPj4G9nXaz0o7qHo_l1KSPZv_sKLmOgDm2lhyphenhyphen8lduu2NJjAW7sMlf9H9IwngN3Po3sSKWsAOkb3swhX2BBs/s1919/3.png)

![screenshot](https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhtFpsrDDOPY3DpjxCZRClppd5aqiKZmTy4LBAYjd1R6pzn3hwAFaPfPIsnka7FgEANZUSKTUfphE1qD8hndv_jftU6g3I47OR6F1W_OeB9Vc9Y_t2v1e-49MEdcMaWKVmlzn7X1xuUngDPcn5OHcFaCjmgG_817C2i-f35FCcdzz0rMze_ZmKukm9izNI/s1919/4.png)

![screenshot](https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEiZTH0f5-i9f6v8QKyl7q2gAX0ww7YshDfF1320dKG0ctZgnWzi3YZOVu6PY0J1dyUkKb90BT4gtOTtUofDbkFANvaX0U7MqVo80RU8Ixsmks8_YBL4e0Sk-okHfLezB7jgFt6D2o-NSxQBdR-2TCww2VgdihGQRNnp3KuZtTJQejqXyf8nzi_qu6q-Uac/s1919/5.png)

![screenshot](https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgUALNdYz7Awz-ihO0S2dZcYBBFTeHsXmGm0HGDxA9bJy1PQ0Le00bDtLENemUHc68IRG4Uhss09qRCpHkD1DA4k5odeD6cuvKDaadtgD1Syi1VPfYjUGFOCsZ2pjBFJ1KFmB6RVKvOpIWcCRY4eUC5_bb_AZ6nmhx1MC4n65tpKHXKoNrOAzzV2zBQq-Y/s1919/6.png)

![screenshot](https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjiMnpp9XnBYQqlxRZR9oiCQ0KkNc4qjUahjcNFYgV9-9Gy6FHFJzUEd2c0CCqMvZije8uMNJC_H1om4Or1h49zbS3gFwKachyphenhyphenMuQlQ3XdyM5VF-FD83STnYVn0m-_Epx4SBaWyOg92FtiXdEXhaX8w_16KdOZJCgqX1cpsmGl2FYhF4C1al-ABNI8bwH0/s1919/7.png)

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
