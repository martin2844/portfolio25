---
title: "Deploy Docker on Ubuntu with Nginx and SSL"
publishDate: "2024-09-21"
slug: "deploy-with-docker-and-ubuntu-in-5-minutes-and-more-nginx-an"
excerpt: "Step-by-step guide to get an Ubuntu VPS ready for containerized apps, served by Nginx with free SSL from Let's Encrypt. No magic, just commands."
readingTime: 3
tags: ["docker"]
---

Today I bring you a super practical step by step so that you have a VPS with Ubuntu ready to receive your app in containers, served by Nginx and with SSL from Let's Encrypt. I put it together because I got tired of eternal deploys: when you are iterating a product, you want going to production to be a procedure, not an odyssey.

Here you will see the flow I use when I build something quickly in Hetzner and I want it online in minutes, without expensive platforms or black magic.

---

## What we do in the video

### 1) Ubuntu + Docker ready in 5 minutes

* Update and prepare the server:
  + sudo apt update && sudo apt upgrade -y
* Install Docker (I follow the official guide):
  + curl -fsSL <https://get.docker.com> | sh
  + sudo usermod -aG docker $USER # to use docker without sudo
  + Close and log back in
* Optional but recommended: Compose plugin
  + sudo apt install -y docker-compose-plugin
  + docker compose version

Tip: leave your app listening on localhost (for example 127.0.0.1:3000) so that Nginx can then proxy it out.

### 2) Your app behind Nginx (reverse proxy)

* Install Nginx:
  + sudo apt install -y nginx
* Open the firewall safely (if you use UFW):
  + sudo ufw allow OpenSSH
  + sudo ufw allow 'Nginx Full'
  + sudo ufw enable
* Point your domain to the VPS with an A record (domain → IP of your server).
* Nginx basic config (example):

  + sudo nano /etc/nginx/sites-available/your-domain.com

  server {  
  listen 80;  
  server\_name your-domain.com www.your-domain.com;

  location / {  
  proxy\_pass <http://127.0.0.1:3000>;  
  proxy\_set\_header Host $host;  
  proxy\_set\_header X-Real-IP $remote\_addr;  
  proxy\_set\_header X-Forwarded-For $proxy\_add\_x\_forwarded\_for;  
  proxy\_set\_header X-Forwarded-Proto $scheme;  
  }  
  }

  + sudo ln -s /etc/nginx/sites-available/your-domain.com /etc/nginx/sites-enabled/
  + sudo nginx -t && sudo systemctl reload nginx

### 3) Free SSL with Certbot (Let’s Encrypt)

* Install Certbot and the Nginx plugin:
  + sudo apt install -y certbot python3-certbot-nginx
* Issue and configure the certificate in one step:
  + sudo certbot --nginx -d your-domain.com -d www.your-domain.com
* Auto-renew:
  + sudo systemctl status certbot.timer # is usually activated
  + You can try dry renewal with: sudo certbot renew --dry-run

With that, you now have HTTPS, automatic redirects to 443 and your app secure to the world.

### 4) A touch of order with Docker Compose

* docker-compose.yml minimal for a web app:

  services:  
  website:  
  image: youruser/yourapp:latest  
  restart: unless-stopped  
  ports:

  + "127.0.0.1:3000:3000" # expose only on localhost  
    env\_file:
  + .env
* Lift:

  + docker compose up -d
* Logs:

  + docker compose logs -f

### 5) Common Gotchas

* DNS: may take a few minutes to propagate; If Certbot fails, check that the domain points correctly.
* Ports: make sure you have 80 and 443 open for validation and certificate.
* Healthchecks: add healthchecks to your services, they save you headaches when they go down.
* Renewal: Let's Encrypt expires every 90 days; I left the timer running and slept peacefully.

---

##Video

---

## Resources I mention

* How to install Docker (official): <https://docs.docker.com/engine/install/ubuntu/>
* Install and configure Nginx (Ubuntu): <https://ubuntu.com/tutorials/install-and-configure-nginx#2-installing-nginx>
* Certbot + Let’s Encrypt on Ubuntu: <https://www.inmotionhosting.com/support/website/ssl/lets-encrypt-ssl-ubuntu-with-certbot/>
* Hetzner (€20 gift): <https://hetzner.cloud/?ref=Sswaf20wbckq>

---

## Closing

The idea is that you do not depend on anyone to release your app. A cheap VPS, Docker to package, Nginx to expose and Let's Encrypt for the little green lock. With this combo you can iterate quickly, scale when necessary and learn from your own infrastructure.

If it works for you, try it today. And if you have questions or want me to add automation with Ansible or scripts, tell me in the comments. See you at the next mate.

---

> Original article in Spanish: [Deploy con Docker y Ubuntu en 5 minutos (y mas) - Nginx y Certbot](https://codigomate.com/deploy-con-docker-y-ubuntu-en-5-minutos-y-mas-nginx-y-certbot/)