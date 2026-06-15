---
title: "Migrate your WordPress (on vps) to coolify with docker"
publishDate: "2024-11-04"
slug: "migrate-your-wordpress-on-vps-to-coolify-with-docker"
excerpt: "Configurar una nueva instancia de WordPress con MariaDB Muy facil en este caso. Simplemente es hacer click en   Projects -> add -> crear el proyecto -> add resource -> wordpress with amariadb  Migrar ..."
readingTime: 4
tags: ["wordpress", "docker"]
---

## Configurar una nueva instancia de WordPress con MariaDB

Muy facil en este caso. Simplemente es hacer click en  
Projects -> add -> crear el proyecto -> add resource -> wordpress with amariadb

### Migrar la base de datos

**El primer** paso es hacer un dump de la tabla.

```
mysqldump -u root -p codigomate > codigomate_backup.sql
```

**El segundo paso** es descargar el dump (podes enviarlo directamente al nuevo servidor desde tu servidor, pero...). Voy a descargarlo primero a mi PC para evitar usar la contrasena (ya que desde mi pc tengo ssh al nuevo server):

```
scp root@45.77.152.168:./codigomate_backup.sql ./
```

**El tercer paso** es subir el dump al nuevo servidor:

```
scp ./codigomate_backup.sql root@49.13.115.135:/ruta/de/destino/
```

**El cuarto paso** es cargar este dump en la MariaDB dockerizada.

**Primero necesitamos:**

* Modificar temporalmente para exponer los puertos: agrega `ports -3306:3306` al docker-compose.
* Instalar `# apt install mysql-client-core-8.0` para poder conectarse desde el servidor a la base de datos en el contenedor Docker.

**Asi quedaria tu docker-compose en mariadb:**

```
  mariadb:
    image: 'mariadb:11'
    volumes:
      - 'mariadb-data:/var/lib/mysql'
    environment:
      - MYSQL_ROOT_PASSWORD=$SERVICE_PASSWORD_ROOT
      - MYSQL_DATABASE=wordpress
      - MYSQL_USER=$SERVICE_USER_WORDPRESS
      - MYSQL_PASSWORD=$SERVICE_PASSWORD_WORDPRESS
    ports:
      - '3306:3306'
```

Luego, **ejecutamos** `root@Canela:~# docker ps | grep q4cwo04oog48gw0cog4gcc44`, donde el **ultimo string** es el UUID asignado por Coolify al volumen.

Obtenemos el ID del contenedor Docker: `a448f3c6c12d`

**Despues copiamos** el dump al contenedor:

```
docker cp ./codigomate_backup.sql a448f3c6c12d:/codigomate_backup.sql
```

Luego usamos el **usuario y la contrasena** del panel de Coolify:

```
root@Canela:~# mysql -h 127.0.0.1 -P 3306 -u root -p'password' wordpress < ./codigomate_backup.sql
mysql: [Warning] Using a password on the command line interface can be insecure.
```

**Podemos verificar ejecutando:**

```
root@Canela:~# mysql -h 127.0.0.1 -P 3306 -u root -p'pass'
```

Dentro de MySQL, elegimos la tabla:

```
use wordpress;
```

Luego hacemos algo como:

```
select * from wp_users LIMIT 5;
```

para ver si estan los usuarios esperados.

Y listo, la base de datos esta migrada. Ahora podemos volver al compose y **eliminar la exposicion de puertos.**

### Migrar archivos

En el servidor original, voy a una carpeta por encima de los archivos de WordPress; en mi caso, es `/var/www`, y la carpeta se llama `codigomate`, entonces hice:

```
zip -r codigomate_files.zip ./codigomate/*
```

Eso crea un archivo zip. Para facilitar el acceso, lo muevo a mi home:

```
mv ./codigomate_files.zip ~/codigomate_files.zip
```

Ahora descargo esto como hicimos con el dump:

```
scp root@45.77.152.168:./codigomate_files.zip ./
```

Basicamente, con el comando anterior estamos descargando desde nuestra vps a nuestro escritorio el archivo.

Luego, transferimos esto al nuevo servidor, osea el desde nuestra pc al nuevo vps.

```
scp ./codigomate_files.zip root@49.13.115.135:./
```

Verificamos que este ahi con `ls`.

Pasemos ahora a agregar esto al contenedor. Los archivos de WordPress de Coolify estan en `/var/www/html`.

Copiamos el archivo zip al contenedor:

```
docker cp ./codigomate_files.zip 4538df65c53a:/var/www/html
```

Ahora accedemos al contenedor:

```
docker exec -it  4538df65c53a bash
```

Instalamos unzip:

```
apt update 
apt install unzip -y
```

Luego, en el contenedor, en `/var/www/html`:

```
unzip codigomate_files.zip -d .
```

#### Establecer permisos correctos

Sin esto no vamos a poder subir imagenes o media.

```
root@201d063304d0:/var/www/html# chown -R www-data:www-data /var/www/html/wp-content/uploads
root@201d063304d0:/var/www/html# chmod -R 755 /var/www/html/wp-content/uploads
```

#### Configurar tamaño maximo de subida de archivos

Sin esto vamos a estar limitados a 2MB de subida.

Entra al contenedor nuevamente, instala vim o nano:

```
apt update
apt install vim -y
```

Edita el archivo `.htaccess` y agrega estas lineas:

```
php_value upload_max_filesize 256M
php_value post_max_size 256M
php_value max_execution_time 300
php_value max_input_time 300
```

### Migrar DNS

Primero, prueba si el dominio: `http://wordpress-q4cwo04oog48gw0cog4gcc44.49.13.115.135.sslip.io` redirige a nuestra URL original (en mi caso, codigomate.com). Si es asi, podemos migrar el DNS de manera segura, solo se trata de apuntar los registros A a la direccion IP de nuestro servidor.

Luego, en la seccion `Service Stack` del proyecto, ve a `Servicios` -> `WordPress` -> `Ajustes` y agrega tus dominios, en mi caso:

`https://codigomate.com,https://codigomate.com`

---

Y listo, son varios pasos pero cualquier migracion de wordpress lleva su tiempo.

A tener en cuenta:

* Esta forma de hostear wordpress consume mas memoria virtual que `php-fpm` con nginx como tenia en el servidor anterior
* Cada wordpress tiene su propia instancia de mariadb (se puede armar para que compartan todos una misma instancia - pero habria que modificar algunos pasos)

---

> Original article in Spanish: [Migra tu WordPress (en vps) a coolify con docker](https://codigomate.com/migra-tu-wordpress-en-vps-a-coolify-con-docker/)