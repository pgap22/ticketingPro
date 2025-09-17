# TicketingPRO v2 — Código Fuente (paquete limpio)
Generado: 2025-09-17

Este paquete contiene **solo la versión v2** con la estructura completa. Incluye un build mínimo en `public/build` (JS/CSS) para que puedas verlo de inmediato. Para la experiencia completa (Tailwind, Alpine y FilePond sin CDN) ejecuta los pasos de instalación.

## Requisitos
- PHP 8.2+
- Composer
- (Opcional) Node 18+ y npm

## Pasos
```bash
cp .env.example .env
composer install

# Opcional: si no quieres usar CDN y deseas el bundle local
npm install
npm run build
```

Después levanta:
```bash
php -S localhost:8000 -t public
```

**Notas**
- En producción los errores se registran en `storage/logs/app.log`.
- Los adjuntos se guardan en `storage/app/attachments`. Crea un enlace simbólico a `public/storage` si deseas servirlos directamente:
  - Linux/Mac: `ln -s $(pwd)/storage/app $(pwd)/public/storage`
  - Windows (PowerShell Admin): `New-Item -ItemType SymbolicLink -Path (Resolve-Path .\public\storage) -Target (Resolve-Path .\storage\app)`

> Este proyecto usa Illuminate (Eloquent, View, Validation) y FastRoute; por tamaño/licencia **no se incluye** la carpeta `vendor/`. Al ejecutar `composer install` se descargará automáticamente.