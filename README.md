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
