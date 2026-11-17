# LavaSoft

Sistema de gestión para lavaderos de vehículos. Permite llevar el control de clientes, vehículos, servicios, agenda, empleados y pagos desde un solo lugar, en lugar de usar cuadernos o Excel.

Es un proyecto de formación del SENA (CATA, Málaga, Santander), desarrollado con metodología Scrum. Se ejecuta de forma local. Para enviar correos de facturas o recuperación de contraseña se necesita conexión a internet y configurar el servicio de correo.

## ¿Qué problema resuelve?

En un lavadero, el precio de un servicio puede variar según el vehículo, los turnos cambian y los pagos se hacen en efectivo, tarjeta o transferencia. LavaSoft organiza esa información:

- Los clientes y sus vehículos.
- Los servicios disponibles y sus precios según el tipo de vehículo.
- Las citas y los empleados asignados.
- Los pagos, sus métodos y las facturas solicitadas.

## Módulos

- **Clientes y vehículos:** registro de clientes, sus vehículos y el historial de servicios.
- **Servicios y precios:** catálogo de servicios con precios según el tipo de vehículo.
- **Agenda:** citas por orden de llegada o previamente agendadas, sus estados y la disponibilidad de empleados.
- **Empleados y turnos:** registro del personal y sus horarios de atención.
- **Pagos:** pagos por cita, métodos de pago, comisiones y facturas.
- **Reportes y configuración:** consultas del negocio y ajustes generales del lavadero.
- **Usuarios, roles y permisos:** acceso al sistema según el rol y los permisos asignados.

## Requisitos

En Windows se puede usar [Laragon](https://laragon.org/), que facilita la instalación y configuración del entorno.

- PHP 8.3 o superior.
- MySQL.
- Composer 2.
- Node.js y npm.
- Extensiones de PHP: PDO, mbstring, openssl, tokenizer, XML, ctype, curl, fileinfo y gd.

Si no usas Laragon, instala estos componentes por separado y verifica que las extensiones de PHP estén activas.

## Instalación

1. Abre Laragon, selecciona **Iniciar todo** y abre su terminal.
2. Clona el proyecto dentro de `C:\laragon\www`.
3. Crea una base de datos vacía llamada `lavasoft` en MySQL. Puedes hacerlo desde HeidiSQL, incluido con Laragon.
4. Desde la carpeta del proyecto, instala las dependencias y crea el archivo de configuración:

```powershell
composer install
Copy-Item .env.example .env
```

5. Abre `.env` y configura la conexión a la base de datos:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lavasoft
DB_USERNAME=root
DB_PASSWORD=
```

En una instalación predeterminada de Laragon, el usuario de MySQL suele ser `root` y la contraseña queda vacía. Si configuraste otros datos, usa esos valores.

6. Prepara la aplicación y crea las tablas con los datos iniciales:

```powershell
php artisan key:generate
npm install
npm run build
php artisan migrate --seed
```

7. Inicia el servidor local:

```powershell
php artisan serve
```

Abre <http://127.0.0.1:8000> en el navegador e inicia sesión con una cuenta creada para el sistema.

> No subas el archivo `.env` a GitHub: puede contener contraseñas y otros datos privados. No ejecutes `php artisan migrate:fresh` en una base de datos con información que quieras conservar, porque elimina sus tablas.

### Usar SQLite

SQLite puede servir para hacer pruebas rápidas. En `.env`, configura:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Crea el archivo vacío de la base de datos desde PowerShell:

```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
```

El proyecto se desarrolló principalmente con MySQL.

## Estructura del proyecto

El código está organizado por capas para mantener separadas las pantallas, las reglas del sistema y el acceso a la base de datos.

| Carpeta o archivo | Uso |
| --- | --- |
| `resources/views` | Vistas Blade, layouts y componentes |
| `app/Http/Controllers` | Reciben las solicitudes y preparan las respuestas |
| `app/Http/Requests` | Validan los datos de los formularios |
| `app/Services` | Contienen las reglas y procesos del sistema |
| `app/Repositories` | Organizan el acceso a la base de datos |
| `app/Repositories/Contracts` | Definen los contratos de los repositorios |
| `app/Providers/RepositoryServiceProvider.php` | Relaciona cada contrato con su repositorio |
| `app/Models` | Modelos de Eloquent |
| `database/migrations` | Cambios en la estructura de la base de datos |
| `database/seeders` y `database/factories` | Datos iniciales y datos para pruebas |

El flujo habitual es: **ruta → controlador → validación → servicio → repositorio → base de datos**.

Las rutas internas requieren iniciar sesión y verifican los permisos del usuario. Laravel también protege los formularios contra solicitudes CSRF y cifra las contraseñas.

## Pruebas y comandos útiles

```powershell
php artisan test
vendor/bin/pint --test
php artisan route:list
php artisan migrate:status
```

Las pruebas usan SQLite en memoria, por lo que no modifican la base de datos MySQL local.

## Trabajo en equipo

Cada integrante trabaja en su propia rama y, cuando termina sus cambios, se integra en `Dev`. Antes de hacer un commit, se revisa con `git status` que no se hayan agregado archivos privados o pesados, como `.env`, `vendor`, `node_modules` o bases de datos locales.

El equipo también lleva el seguimiento del proyecto utilizando la metodología del scrum en su propio documento.
