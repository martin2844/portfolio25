---
title: "Migrate NGINX & PHP-FPM to Coolify"
publishDate: "2024-11-04T14:00:00Z"
excerpt: "A step by step guide on how I migrated my Wordpress from NGINX and PHP-FPM to Coolify"
readingTime: 8
tags: ["wordpress", "coolify", "docker", "self-hosting"]
slug: "migrate-wordpress-to-coolify-dockerized"
---

# Migrate NGINX & PHP-FPM to Coolify

### Setup a new wordpress instance with Mariadb

Very easy in this case you just add a new project, add a resource and select wordpress with mariadb.

### Migrate DB

1st step is to get the dump of the table

`mysqldump -u root -p codigomate > codigomate_backup.sql`

2nd step is to download the dump (you can just send it to the next server from your server but...)
Im going to download it to my pc first so I avoid using the password:

```
scp root@45.77.152.168:./codigomate_backup.sql ./
```

3rd step is to get the dump on to the server

```
scp ./codigomate_backup.sql root@49.13.115.135:/path/to/destination/

```

4th step is to put this dump onto the the dockerized mariadb

So first we need to 
- Temporarily modify to expose the ports - add ports -3306:3306 to docker compose
- install `apt install mysql-client-core-8.0` - to be able to connect from the server to the docker container db

`root@Canela:~# docker ps | grep q4cwo04oog48gw0cog4gcc44` where this last string is the UUID set by coolify to the volume

We get the docker container ID: `a448f3c6c12d`

Then we copy the dump there:

```
 docker cp ./codigomate_backup.sql a448f3c6c12d:/codigomate_backup.sql
```

Then we use the password and user from coolify panel:

```
root@Canela:~# mysql -h 127.0.0.1 -P 3306 -u root -p'password' wordpress < ./codigomate_backup.sql
mysql: [Warning] Using a password on the command line interface can be insecure.
```

Then we can check by doing

```
root@Canela:~# mysql -h 127.0.0.1 -P 3306 -u root -p'pass'
```

We can go into mysql -> 

`use wordpress;`

then we can do something like

`select * from wp_users LIMIT 5` 

To see if you have the expected users

So thats it, DB migrated.

We can go back to the compose now and eliminate the port exposure.

### Migrate files

On the original server Im heading to one dir above the wordpress files,

in my case is `/var/www`

the folder's name is `codigomate`

so I did

```
zip -r codigomate_files.zip ./codigomate/*
```

Now, that produces a zip file

for ease of access im moving it home so

in the same dir

```
mv ./codigomate_files.zip ~/codigomate_files.zip
```

Now I will download this as we did with the dump:

```
 scp root@45.77.152.168:./codigomate_files.zip ./
```

Now transfer this to the new server:

```
 scp ./codigomate_files.zip root@49.13.115.135:./
```

Verify its there with `ls`

Now, move on to adding this to the container.

Coolify's wordpress files are actually on `/var/www/html`

anyways.... we can copy the zip file now to our container

```
docker cp ./codigomate_files.zip 4538df65c53a:/var/www/html
```

Now we access the container:

`docker exec -it  4538df65c53a bash`

And we install unzip

```
apt update 
apt install unzip -y
```

Then in the container, at /var/www/html

```
unzip codigomate_files.zip -d .
```

#### Set correct permitions

```
root@201d063304d0:/var/www/html# chown -R www-data:www-data /var/www/html/wp-content/uploads
root@201d063304d0:/var/www/html# chmod -R 755 /var/www/html/wp-content/uploads
```

#### set upload file size

enter the docker container again:

install vim or nano

```
apt update
apt install vim -y
```

edit the `.htaccess` file and add these lines

```
php_value upload_max_filesize 256M

php_value post_max_size 256M

php_value max_execution_time 300

php_value max_input_time 300
```



### MIGRATE DNS

First try if the domain:
`http://wordpress-q4cwo04oog48gw0cog4gcc44.49.13.115.135.sslip.io`

Redirects to our original url - my case codigomate.com, it does.

So we assume we can safely migrate the DNS, so its just a matter of pointing the a records there.

So add A records pointing to our server's Ip address and then on your projects `Service Stack` section, go to `Services` -> `Wordpress` -> `Settings`

and add your domains there, in my case:

`https://codigomate.com,https://www.codigomate.com`


---

To take into account:

- This way of hosting WordPress uses more virtual memory than PHP-FPM with Nginx, as I had on the previous server.  
- Each WordPress instance has its own MariaDB instance (it can be set up so they all share a single instance, but a few steps would need to be modified).