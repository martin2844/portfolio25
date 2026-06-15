---
title: "How to host your node and react application on your own VPS (1/2)"
publishDate: "2021-11-05"
slug: "how-to-host-your-node-and-react-application-on-your-own-vps-"
excerpt: "How to host your Node + React app on your own VPS (Part 1) In this video I proposed something that several had been asking me for: building a full stack app (Node + React + Mongo) on my own VPS, witho..."
readingTime: 4
tags: ["docker"]
---

# How to host your Node + React app on your own VPS (Part 1)

In this video I proposed something that several had been asking me for: building a full stack app (Node + React + Mongo) on my own VPS, without magic platforms or strange costs. All with Ubuntu, NGINX and a couple of tools that, once you understand them, make you feel like you have your mini-cloud at home. It is the first part of two: in this one we focus on leaving everything online and stable, and in the next one we add SSL and more fine-tuning.

## What are you going to ride?

* A VPS with Ubuntu (I used vultr.com, but you can use whatever you want).
* NGINX as a reverse proxy in front of your Node app.
* Node and Yarn to run and build.
* MongoDB as a database.
* PM2 to keep the app alive and restart in case of crashes.
* Domain pointing to your server so that it remains pro.

The idea is that you end up with your React app served by Express/Node, accessible by domain, behind NGINX. No strange ports in sight.

## The VPS and SSH access

* I create an instance in Vultr, choose Ubuntu, give it the basic resources and that's it.
* I connect via SSH (key or password, whichever is more comfortable for you).
* Small security review and quick system update.

## NGINX: installation, firewall and testing

* I install NGINX and explain in Creole what role it plays: it serves as a front door, handles traffic on 80/443 and passes the requests to Node in the back.
* I enable the firewall for NGINX (HTTP/HTTPS) and check the welcome page from Chrome to confirm that we are fine.

## Node, Mongo and the project

* I install NodeJS on Ubuntu and leave the key commands.
* Also MongoDB to have the database available on the server.
* I clone the example repo and show you how it is organized so you understand what runs on the backend and what runs on the frontend.

Example repo I use:

* <https://github.com/martin2844/deploy-example>

## React Build and how Express serves it

* I install Yarn and build the frontend.
* I show how Express serves the static build of React without having to have two public servers.
* I run everything locally on the server to check that the stack responds.

## Keep the app alive with PM2

* I configure PM2 to launch the app.
* I leave the process in the background and add “pm2 startup” so that it starts only if the server restarts.
* A classic that saves you headaches: logs and automatic restart.

## Domain and reverse proxy with NGINX

* I delegate a domain (Namecheap) pointing to the IP of the VPS.
* I set up the NGINX server block to act as a reverse proxy towards my Node app (port 3000, for example).
* I leave you a minimal example of the configuration block:

```
server {
  server_name your-domain.com www.your-domain.com;

  location / {
    proxy_pass http://127.0.0.1:3000;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_cache_bypass $http_upgrade;
  }

  listen 80;
}
```

With this you are now serving your app over HTTP on your domain. We see the little padlock (SSL with Let's Encrypt) in Part 2 with Certbot.

## Resources I mention

* VPS: <https://vultr.com>
* Domains: <https://namecheap.com>
* How to install NGINX: <https://www.digitalocean.com/community/tutorials/how-to-install-nginx-on-ubuntu-20-04-es>
* How to install MongoDB: <https://www.digitalocean.com/community/tutorials/how-to-install-mongodb-on-ubuntu-20-04-es>
* How to install NodeJS: <https://ubunlog.com/nodejs-npm-instalacion-ubuntu-20-04-18-04/>
* How to install Certbot: <https://www.digitalocean.com/community/tutorials/how-to-secure-nginx-with-let-s-encrypt-on-ubuntu-20-04-es>
* Configure NGINX as a reverse proxy: <https://hackernoon.com/configure-nginx-s-a-reverse-proxy-for-your-nodejs-application-9tk032e8>
* Example repo: <https://github.com/martin2844/deploy-example>
* Part 2 (SSL, more configuration): [Watch on YouTube (spanish)](<https://www.youtube.com/watch?v=dzEohD1nUOw>)
* Do you prefer containers? Deployment with Docker in 5 minutes: [Watch on YouTube (spanish)](<https://www.youtube.com/watch?v=Hz_Jr2I_n8w&ab_channel=CodigoMate>)
* Extra: if you want to try Hetzner with credit: <https://hetzner.cloud/?ref=Sswaf20wbckq>

## Watch the full video

## Closing

Nothing like having your stuff running on your own server and understanding every piece of the stack. In part 2 we put the lock on it with Let's Encrypt, we adjust NGINX and leave everything neat for production. If it helped you, tell me what you want me to add or what hindered you in your deployment. We are going to continue rowing it, but with good practices and a mate on the side everything becomes more bearable. See you in the next one.

---

> Original article in Spanish: [Como hostear tu aplicacion node y react en tu propia VPS (1/2)](https://codigomate.com/como-hostear-tu-aplicacion-node-y-react-en-tu-propia-vps-1-2/)