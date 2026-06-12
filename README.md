# ElectroShop

Tienda en Laravel para catálogo, carrito, pedidos y administración.

## Requisitos

- PHP 8.2 o superior
- Composer
- Node.js y npm
- MySQL o MariaDB

## Instalación

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run dev
php artisan serve
```

## Módulos

- Catálogo y detalle de productos.
- Carrito y compra.
- Gestión de productos, categorías, marcas, usuarios y pedidos.
- Estadísticas de ventas.

## Instalación en la PC del instituto

Estos pasos son para Windows usando Git Bash, XAMPP, PHP 8.2.12 y Composer 2.10.x.

### 1. Abrir Git Bash

Ejecutar Git Bash desde Windows:

```powershell
& "C:\Program Files\Git\bin\bash.exe"
```

### 2. Acceder a la ruta donde se desea clonar el proyecto

Ejemplo:

```bash
cd /mnt/d/ISW-V_G02
```

Si quieres usar otra carpeta, entra a esa ruta antes de clonar.

### 3. Clonar el proyecto desde GitHub

```bash
git clone https://github.com/s4tker/Store.git
cd Store
```

### 4. Instalar dependencias PHP

```bash
composer install
```

### 5. Copiar el archivo .env desde el USB

Reemplaza `/mnt/e` por la ruta real de tu USB. Si tu USB aparece como unidad `E:`, normalmente sera `/mnt/e`.

```bash
cp /mnt/e/.env .env
```

Si no tienes el `.env` en el USB, usa este comando:

```bash
cp .env.example .env
php artisan key:generate
```

### 6. Copiar la base de datos Store.sql desde el USB

Reemplaza `/mnt/e` por la ruta real de tu USB:

```bash
cp /mnt/e/Store.sql database/Store.sql
```

### 7. Configurar la base de datos en .env

El `.env` debe tener estos datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Store
DB_USERNAME=root
DB_PASSWORD=
```

### 8. Encender XAMPP

En XAMPP iniciar:

```text
Apache
MySQL
```

### 9. Crear e importar la base de datos

No ejecutar `php artisan migrate` si vas a importar `Store.sql`, porque el SQL ya trae las tablas y los datos.

```bash
/c/xampp/mysql/bin/mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS Store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
/c/xampp/mysql/bin/mysql.exe -u root Store < database/Store.sql
```

### 10. Crear enlace para las imagenes

```bash
php artisan storage:link
```

### 11. Instalar dependencias frontend

```bash
npm install
npm run build
```

### 12. Limpiar cache de Laravel

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 13. Levantar el proyecto

```bash
php artisan serve
```

Abrir en el navegador:

```text
http://127.0.0.1:8000
```

### Comandos completos resumidos

```bash
cd /mnt/d/ISW-V_G02
git clone https://github.com/s4tker/Store.git
cd Store

composer install

cp /mnt/e/.env .env
cp /mnt/e/Store.sql database/Store.sql

/c/xampp/mysql/bin/mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS Store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
/c/xampp/mysql/bin/mysql.exe -u root Store < database/Store.sql

php artisan storage:link

npm install
npm run build

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan serve
```
