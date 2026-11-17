# LavaSoft

Aplicación web para organizar clientes, vehículos, servicios, turnos, citas, pagos y reportes de lavaderos. Incluye inicio de sesión y permisos por módulo.

## Requisitos

- PHP 8.3 o superior con PDO, mbstring, openssl, tokenizer, XML, ctype, curl, fileinfo y gd.
- Composer 2, Node.js/npm y MySQL o SQLite.

## Instalación local

```powershell
composer install
Copy-Item .env.example .env
```

Configura `.env` con la conexión de base de datos y define el nombre, correo y contraseña segura del administrador. Para SQLite puedes usar `DB_CONNECTION=sqlite` y `DB_DATABASE=database/database.sqlite`; crea primero el archivo vacío.

```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
php artisan key:generate
npm install
npm run build
php artisan migrate --seed
php artisan serve
```

Abre `http://127.0.0.1:8000` e inicia sesión con las credenciales del administrador configuradas en `.env`. No compartas ni publiques este archivo. Los comandos que reinician tablas deben ejecutarse solo en bases desechables.

## Módulos

Clientes, vehículos e historiales; servicios y precios por tipo de vehículo; agenda y disponibilidad; empleados y turnos; pagos, comisiones y facturas; reportes; configuración; usuarios, roles y permisos.

## Arquitectura

- `resources/views`: vistas Blade, layouts y componentes.
- `app/Http/Controllers` y `app/Http/Requests`: entrada HTTP y validación.
- `app/Services`: reglas y procesos de negocio.
- `app/Repositories/Contracts` y `app/Repositories`: contratos y acceso Eloquent.
- `app/Providers/RepositoryServiceProvider.php`: enlaces de contratos.
- `app/Models`, `database/migrations`, `database/seeders` y `database/factories`: persistencia y datos de prueba.

Las rutas operativas usan autenticación y middleware de módulos/permisos. Laravel protege formularios con CSRF y cifra las contraseñas.

## Verificación

```powershell
php artisan route:list
php artisan migrate:status
php artisan test
vendor/bin/pint --test
```

La suite usa SQLite en memoria. Conserva el resultado de pruebas y la evidencia de revisión visual antes de presentar.

## Git y Scrum

No publiques `.env`, credenciales, bases locales, dependencias ni archivos generados. Revisa cambios antes de crear commits y publica la rama acordada por el equipo. Consulta `docs/MATRIZ_CUMPLIMIENTO_AP5.md` y `docs/SEGUIMIENTO_SCRUM.md`; completa las fechas, responsables, enlace al tablero y retrospectivas con evidencia real.
