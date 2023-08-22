# Guía de Configuración y Ejecución para Drugstore Backend
## Prerrequisitos
-Docker y Docker Compose instalados.
-PHP 8.2 o superior.
-Composer para la gestión de dependencias de PHP.

## Configuración
1. Clonar el Repositorio:
```bash
git clone https://github.com/lacadaemon94/drugstore-backend.git
cd drugstore-backend
Instalar Dependencias de PHP:
```
2. Construir el docker
```bash
docker-compose build
```
3. Iniciar los contenedores Docker.
```bash
docker-compose up
```
La aplicacion deberia de ser accesible en el puerto :8080
4. Accesar a la terminal de la App de Symfony.
```bash
docker exec -it symfony_app bash
```
5. Instalar el runtime de Symfony y dependencias de PHP
```bash
composer install
``` 
o solo:
```bash
composer require symfony/runtime
```
5. Crear Migracion:
```bash
php bin/console make:migration
``` 
6. Aplicar la Migracion:
```bash
php bin/console make:migration
``` 
7. Correr scripts de SQL para alimentar la BD (afuera del terminal de symfony_app) en el directorio raiz:
```bash
docker-compose exec -e PGPASSWORD='!ChangeMe!' postgres psql -U app -d app -f /var/www/sql_scripts/01_producto.sql
``` 
```bash
docker-compose exec -e PGPASSWORD='!ChangeMe!' postgres psql -U app -d app -f /var/www/sql_scripts/02_tipo.sql
``` 
```bash
docker-compose exec -e PGPASSWORD='!ChangeMe!' postgres psql -U app -d app -f /var/www/sql_scripts/03_inventario.sql
``` 
