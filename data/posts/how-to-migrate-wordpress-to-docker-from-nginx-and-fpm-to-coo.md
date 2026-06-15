---
title: "How to migrate wordpress to docker - From NGINX and FPM to Coolify"
publishDate: "2024-12-02"
slug: "how-to-migrate-wordpress-to-docker-from-nginx-and-fpm-to-coo"
excerpt: "For a while now I have been migrating things to Docker to have neater and more replicable deployments. I had a WordPress that ran on a VPS with NGINX + PHP-FPM, and I was interested in moving it to Co..."
readingTime: 4
tags: ["wordpress", "docker"]
---

For a while now I have been migrating things to Docker to have neater and more replicable deployments. I had a WordPress that ran on a VPS with NGINX + PHP-FPM, and I was interested in moving it to Coolify to simplify management, backups and monitoring without getting involved in giant panels. In this video/post I show you how I approached it: database, files and domain. No smoke, step by step and without breaking (too much) anything.

If you still don't have Coolify running, I left you a short video to get it up and running in 5 minutes. I link it below.

## The important thing about migration (short and at the bottom)

### 1) Before touching anything: checklist

* Make a full backup (DB + wp-content).
* Lower the DNS TTL if you are going to move the domain (to propagate faster).
* Activate “maintenance” in WordPress if you expect traffic.
* Write down PHP versions, plugins and theme. Avoid surprises.

### 2) Backups you need

* Database:
  + mysqldump -u USER -p BASE > backup.sql
* Files:
  + The critical thing is wp-content (uploads, plugins, themes).
  + You can compress it: tar czf wp-content.tar.gz wp-content

Tip: try restoring locally, even if it's just a little bit. Saves you headaches later.

### 3) Prepare the environment in Coolify

* Create a “Project” and inside, a database service (MySQL/MariaDB).
* Create the WordPress service as a container. In my case I used the official image (Apache), because it is simple and stable.
* Key environment variables in WordPress:
  + WORDPRESS\_DB\_HOST
  + WORDPRESS\_DB\_NAME
  + WORDPRESS\_DB\_USER
  + WORDPRESS\_DB\_PASSWORD
  + Optional: WP\_HOME and WP\_SITEURL to set the domain.
* Volumes/persistence:
  + Mount at least /var/www/html/wp-content as persistent storage.

Coolify resolves the proxy and SSL certificates automatically when you assign the domain to the service. A great goal.

### 4) Restore the database

* Create the base with the credentials you defined in Coolify.
* Import the backup:
  + mysql -h HOST -u USER -p BASE < backup.sql
* If you prefer GUI, you can throw an Adminer in another container and import from there.

### 5) Upload wp-content

* Upload your wp-content to the container volume (via SFTP from the server, rsync, or whatever way is comfortable for you).
* Make sure the permissions are reasonable (www-data is usually the user inside the container).

### 6) Fine adjustments

* Search/replace URLs if you changed domain or http→https:
  + wp search-replace '<http://yoursite.com>' '<https://yoursite.com>' --all-tables
* Check permalinks from the admin and save them (force .htaccess/permalinks).
* Increase limits if necessary (upload\_max\_filesize, memory\_limit) with PHP ini/env or .htaccess depending on your image.

### 7) Domain and SSL

* Point the A/AAAA of the domain to the IP of the VPS with Coolify.
* Assign the domain to the service in Coolify and activate SSL. Automatic certificate and that's it.

### 8) Testing and monitoring

* Dashboard, posts, media, login and searches.
* Cron and scheduled tasks (wp-cron or real cron).
* Automatic volume and DB backups (schedule them, post).

## Common problems (and how I solved them)

* “I upload images and they don't appear”: permissions or volume path. Verify that wp-content is mounted and writable.
* “Weird redirects”: do search-replace of URLs and define WP\_HOME/WP\_SITEURL.
* “Error 502/504”: check the VPS memory/CPU. WordPress sometimes asks for a little more, especially with large images and heavy plugins.
* “My plugin needs PHP extensions”: Choose a WordPress image with the necessary extensions or add them in a custom Dockerfile.

## Resources that leave you ready

* Deploy Coolify in 5 minutes: [Watch on YouTube (spanish)](<https://youtu.be/DAaXdNrcTV0>)
* Written guide (step by step with commands): <https://www.codigomate.com/migra-tu-wordpress-en-vps-a-coolify-con-docker/>
* New VPS? You have €20 free at Hetzner: <https://hetzner.cloud/?ref=Sswaf20wbckq>

## Watch the video

[Watch on YouTube (spanish)](https://www.youtube.com/watch?v=DAaXdNrcTV0)

Closing

Migrating a WordPress is not magic: it is three pieces that move together – base, files and domain – and with Docker + Coolify everything is more predictable and easier to maintain. If it helped you, I'll share it with that person who has WordPress hanging by a thread on an old VPS. And if it suits you, leave me in the comments what you would like to migrate next. See you next time with another mate and more displays.

---

> Original article in Spanish: [Como migrar wordpress a docker - De NGINX y FPM a Coolify](https://codigomate.com/como-migrar-wordpress-a-docker-de-nginx-y-fpm-a-coolify/)