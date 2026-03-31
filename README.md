# Sistema de Gestión de Locales Comerciales

Aplicación Full Stack desarrollada con **Laravel 11** que permite visualizar y actualizar locales comerciales mediante una API REST consumida desde una interfaz web Blade.

---

## Tecnologías

- **Backend:** Laravel 11 (PHP 8.3+)
- **Frontend:** Laravel Blade + Bootstrap 5 + JavaScript (Fetch API)
- **Base de datos:** SQLite (incluida, sin configuración adicional)

---

## Requisitos

- PHP >= 8.3 con extensiones `pdo_sqlite` y `sqlite3` habilitadas
- Composer

---

## Instalación y ejecución

```bash
# 1. Clonar el repositorio
git clone https://github.com/harlericho/prueba_gestion_locales.git
cd prueba_gestion_locales

# 2. Instalar dependencias
composer install

# 3. Copiar el archivo de entorno
cp .env.example .env

# 4. Generar la clave de aplicación
php artisan key:generate

# 5. Crear el archivo de base de datos SQLite
touch database/database.sqlite

# 6. Ejecutar migraciones y seeder (carga 12 locales de prueba)
php artisan migrate --seed

# 7. Levantar el servidor de desarrollo
php artisan serve
```

Abrir en el navegador: `http://localhost:8000`

---

## Estructura del Proyecto

```
app/
├── Http/Controllers/
│   ├── Api/LocalController.php   # Controlador de la API REST
│   └── LocalController.php       # Controlador web (devuelve la vista)
├── Models/Local.php              # Modelo Eloquent de locales
database/
├── migrations/
│   └── ..._create_locales_table.php
├── seeders/
│   ├── DatabaseSeeder.php
│   └── LocalesSeeder.php         # 12 locales de prueba
resources/views/
├── layouts/app.blade.php         # Layout base
└── locales/index.blade.php       # Vista principal con tabla, filtros y modal
routes/
├── api.php                       # Rutas de la API REST
└── web.php                       # Rutas web
```

---

## API REST

| Método      | Endpoint            | Descripción                 |
| ----------- | ------------------- | --------------------------- |
| GET         | `/api/locales`      | Listado paginado de locales |
| PUT / PATCH | `/api/locales/{id}` | Actualizar un local por ID  |

### Parámetros de filtrado (GET /api/locales)

| Parámetro | Tipo    | Descripción                  |
| --------- | ------- | ---------------------------- |
| `nombre`  | string  | Filtra por nombre (LIKE)     |
| `estado`  | integer | Filtra por estado: `1` o `0` |
| `page`    | integer | Página para la paginación    |

### Campos del recurso Local

| Campo            | Tipo    | Requerido | Descripción              |
| ---------------- | ------- | --------- | ------------------------ |
| `nombre`         | string  | Sí        | Nombre del local         |
| `direccion`      | string  | Sí        | Dirección del local      |
| `estado`         | integer | Sí        | 1 = activo, 0 = inactivo |
| `tipo_documento` | string  | No        | `RUC` o `CEDULA`         |
| `nro_documento`  | string  | No        | Número de documento      |

---

## Funcionalidades implementadas

### Requerimientos base

- [x] Listado de locales desde `/api/locales`
- [x] Formulario de actualización por local (PUT a `/api/locales/{id}`)

### Opcionales implementados

- [x] **Modal sin salir del listado** — el formulario de edición abre en un modal Bootstrap
- [x] **Filtros** — por nombre (búsqueda parcial) y por estado (activo/inactivo)
- [x] **Paginación** — 5 registros por página, navegable con botones

---

## Decisiones técnicas

- **SQLite** se eligió para simplificar la configuración, sin necesidad de instalar MySQL o PostgreSQL.
- **Fetch API nativa** se usa en lugar de Axios para evitar dependencias adicionales de npm/build.
- La **actualización via modal** carga los datos del local directamente desde la respuesta JSON del listado, sin llamadas adicionales a la API.
- El **Web Controller** (`LocalController@index`) solo devuelve la vista; toda la lógica de datos ocurre en el frontend consumiendo la API REST, respetando la separación de capas solicitada.
- Las **rutas API** no requieren autenticación, tal como indica la prueba.

---

## Seeder — Datos de prueba

Se incluyen **12 locales** pre-cargados con variedad de estados, tipos de documento y datos opcionales nulos.

```bash
# Para recargar los datos de prueba desde cero:
php artisan migrate:fresh --seed
```
