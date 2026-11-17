LavaSoft

software para la gestión administrativa de lavaderos. Permite llevar el control de clientes, vehículos, servicios, agenda, empleados y pagos desde un solo lugar, en lugar de usar cuadernos o Excel.

Es un proyecto de formación del SENA (CATA, Málaga, Santander), desarrollado con metodología Scrum. Está hecho con Laravel y pensado para correr de forma local, sin depender de internet ni de APIs externas.

¿Qué problema resuelve?

En un lavadero, el mismo servicio cuesta distinto según el vehículo (moto, carro, camioneta/turbo), los turnos cambian y los pagos pueden ser en efectivo, tarjeta o transferencia. LavaSoft organiza todo eso:

Quién es el cliente y qué vehículos tiene.
Qué servicios se ofrecen y cuánto cuesta cada uno según el tipo de vehículo.
Qué citas hay agendadas y qué empleado las atiende.
Qué se pagó, cómo y si el cliente pidió factura.
Módulos
Clientes y vehículos: registro de clientes, los vehículos de cada uno y su historial de servicios.
Servicios y precios: catálogo de servicios con un precio distinto según el tipo de vehículo (moto, carro, camioneta/turbo).
Agenda: citas por orden de llegada o agendadas, con estados (pendiente, confirmada, cancelada) y disponibilidad de los empleados.
Empleados y turnos: personal del lavadero y los turnos que atiende cada uno.
Pagos: pagos por cita (efectivo, tarjeta o transferencia), comisiones de los empleados y facturas cuando el cliente la pide.
Reportes y configuración: consultas del negocio y ajustes generales del lavadero.
Usuarios, roles y permisos: inicio de sesión con rol Administrador o Recepcionista/Empleado, y cada uno ve solo lo que su rol permite.
Requisitos

La forma más fácil de correr el proyecto es con Laragon (Windows), que ya incluye todo lo necesario:

PHP 8.3 o superior
MySQL
Composer 2
Node.js y npm

Si no usas Laragon, instala esas cuatro cosas por separado. PHP debe tener activadas las extensiones PDO, mbstring, openssl, tokenizer, XML, ctype, curl, fileinfo y gd (Laragon ya las trae activas).

Instalación
Abre Laragon, inicia los servicios (Iniciar todo) y abre su terminal. Clona el proyecto dentro de C:\laragon\www.
Crea una base de datos vacía en MySQL llamada lavasoft (puedes hacerlo desde HeidiSQL, que viene con Laragon).
Instala las dependencias de PHP:
bash
composer install
Crea tu archivo de configuración:
bash
copy .env.example .env
Abre .env y configura la conexión a la base de datos y los datos del administrador (nombre, correo y una contraseña segura):
env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lavasoft
DB_USERNAME=root
DB_PASSWORD=

En Laragon el usuario por defecto es root y la contraseña va vacía. Si cambiaste alguno de esos datos, ajústalos aquí.

Genera la clave de la app, instala y compila el frontend, y crea las tablas con datos iniciales:
bash
php artisan key:generate
npm install
npm run build
php artisan migrate --seed
Levanta el servidor:
bash
php artisan serve

Abre http://127.0.0.1:8000 e inicia sesión con el administrador que definiste en .env.

Cuidado: nunca subas el archivo .env a GitHub. Y no uses php artisan migrate:fresh en una base con datos reales: borra todas las tablas.

¿Y si no tengo MySQL?

Como alternativa para pruebas rápidas puedes usar SQLite: pon DB_CONNECTION=sqlite y DB_DATABASE=database/database.sqlite en .env, y crea el archivo vacío (New-Item -ItemType File -Path database/database.sqlite -Force en PowerShell, o touch database/database.sqlite en Linux/Mac). El proyecto se desarrolló con MySQL.

Estructura del proyecto

El código sigue una arquitectura por capas para que cada cosa esté en su lugar:

Carpeta	Para qué sirve
resources/views	Vistas Blade (pantallas, layouts y componentes)
app/Http/Controllers	Reciben la petición y devuelven la respuesta
app/Http/Requests	Validan los datos que llegan de los formularios
app/Services	Reglas de negocio
app/Repositories	Acceso a la base de datos (con sus contratos en Contracts)
app/Providers/RepositoryServiceProvider.php	Conecta cada contrato con su repositorio
app/Models	Modelos Eloquent (las tablas como clases)
database/migrations, seeders, factories	Estructura de la base de datos y datos de prueba

El flujo normal es: ruta → controlador → request (valida) → service (lógica) → repository (base de datos).

Las rutas internas requieren iniciar sesión y revisan los permisos del usuario. Además, Laravel protege los formularios con CSRF y guarda las contraseñas cifradas.

Pruebas
bash
php artisan test            # corre las pruebas
vendor/bin/pint --test      # revisa el estilo del código
php artisan route:list      # lista las rutas disponibles
php artisan migrate:status  # muestra qué migraciones se han corrido

Las pruebas usan una base SQLite en memoria, así que no tocan los datos de tu MySQL.

Trabajo en equipo
Equipo: Caren Valentina Páez Sandoval y Brayan Fajardo.
Metodología: Scrum, con sprints, daily y sprint review.
Git: cada integrante trabaja en su propia rama y luego se integra a Dev. Antes de hacer commit, revisa con git status que no se cuelen .env, vendor, node_modules ni la base local.
Documentación: el seguimiento está en docs/SEGUIMIENTO_SCRUM.md y la matriz de cumplimiento en docs/MATRIZ_CUMPLIMIENTO_AP5.md.
