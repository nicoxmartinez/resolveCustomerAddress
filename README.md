## Gestión de Clientes y Domicilios Georeferenciados

Esta aplicación desarrollada en Laravel proporciona un servicio robusto para el alta, actualización y gestión de clientes y sus respectivos domicilios. 
Incluye integración automática en tiempo real con el servicio gubernamental Georef API para resolver geolocalización y normalizar datos de direcciones.

## Entorno SQLite + Docker

Este proyecto documenta la configuración paso a paso para levantar un servidor de base de datos 
**SQLite con interfaz gráfica web** (`coleifer/sqlite-web`) en Docker, manteniendo sincronización 
bidireccional y permisos de lectura/escritura con un proyecto **Laravel**.

## Requisitos Previos

* [Docker](https://docs.docker.com/get-docker/) instalado.
* [Docker Compose](https://docs.docker.com/compose/install/) instalado.

## Dar permisos de escritura a la carpeta y al archivo en tu sistema host

```bash
sudo chown -R $USER:$USER database/
chmod -R 777 database/ 
```

## Ejecuta Docker Compose 

```bash
docker compose up -d
```

El contenedor lee y escribe sobre el mismo archivo físico predefinido por laravel de SQLite alojado en el directorio database/.
Cuando ejecuta Docker Compose con el mapeo del volumen, Docker no copia el archivo, sino que crea un "acceso directo" bidireccional (montaje bind), manteniendo de permisos de usuario.

## Ejecutar el contenedor en modo interactivo:

```bash
 docker exec -it customer_server sqlite3 database/
```
Tambien puedes administrar tu base de datos desde una interfaz web navegable: ``http://localhost:5435``
En tu archivo .env, puedes usar la constante database_path() indicando solo el nombre del archivo, o pasar la ruta absoluta hacia tu proyecto.
Ejemplo: DB_DATABASE=/home/user/laravel-project/database/database.sqlite

## Acerca de Laravel

Laravel es un framework para aplicaciones web con una sintaxis expresiva y elegante. Creemos que el desarrollo debe ser una experiencia creativa y agradable para resultar verdaderamente gratificante. Laravel elimina las complicaciones del desarrollo al facilitar tareas comunes en muchos proyectos web, tales como:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel es accesible y potente, y proporciona las herramientas necesarias para aplicaciones grandes y robustas.

## License

El framework Laravel es un software de código abierto con licencia bajo la [MIT license](https://opensource.org/licenses/MIT).
