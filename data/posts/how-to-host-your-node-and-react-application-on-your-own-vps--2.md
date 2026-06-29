---
title: "Host Your Node + React App on a VPS (2/2)"
publishDate: "2022-02-07"
slug: "how-to-host-your-node-and-react-application-on-your-own-vps--2"
excerpt: "Finish the deployment with free HTTPS via Let's Encrypt, fine-tune Nginx and see the live app. Part 2 closes the VPS setup from Part 1."
readingTime: 4
tags: ["react", "nginx", "javascript", "nodejs", "server"]
---

# How to host your Node + React app on your own VPS (2/2)

In this second part we finish the deployment: we put free HTTPS with Let's Encrypt, we leave Nginx fine as a reverse proxy, we test the app live and, finally, I show you how to see your MongoDB data and how to add a second app to the same server without breaking anything.

## What are you going to learn in this episode

* Add free HTTPS with Certbot and automatically renew certificates.
* Configure Nginx as a reverse proxy for your Node backend and serve your React frontend.
* Test the app in production and read logs accordingly.
* Connect to MongoDB server securely to look at your data.
* Install a second application (another domain/subdomain, another port, another certificate).

---

## 1) HTTPS with Certbot (Let’s Encrypt) without return

The basis: have a domain pointing to your VPS and Nginx already installed and running.

* DNS: create an A record for the IP of your VPS (for example, in Namecheap).
* Nginx: define a `server_name` with your domain and leave the site enabled.
* Certbot: you install `certbot` + Nginx plugin, run `sudo certbot --nginx -d your-domain.com -d www.your-domain.com` and that's it, it configures the server block with 443 and redirection 80→443.
* Renewal: `systemctl status certbot.timer` to make sure the cron/daemon is active. You can test with `sudo certbot renew --dry-run`.

Typical gotchas:

* Make sure you have ports 80 and 443 open on the firewall.
* If you use multiple sites, each one needs its correct `server_name` and its own certificate.

Resources I mention:

* How to install Nginx (DigitalOcean, in Spanish)
* How to install Certbot with Nginx (DigitalOcean, in Spanish)

---

## 2) Testing the live app

The idea is that:

* The Node backend runs on an internal port (for example, 3001) and Nginx acts as a reverse proxy on 443.
* The React frontend is served as static from Nginx (`react-scripts` build) or, if you prefer, proxyed by Nginx to a static server.

Quick checklist:

* Backend: run your app with PM2 or systemd. Example: `pm2 start npm --name api -- start` and listen on 127.0.0.1:3001.
* Nginx: `location /api` proxy\_pass to `http://127.0.0.1:3001/`.
* Frontend: you generate the build (`npm run build`) and serve it in `root /var/www/tu-frontend/build;` with `try_files $uri /index.html;`.
* Logs: If something is not responding, look at `sudo journalctl -u nginx -f`, `pm2 logs`, or `sudo tail -f /var/log/nginx/error.log`.

Example repo:

* <https://github.com/martin2844/deploy-example>

---

## 3) Bonus: look at your MongoDB data without opening the port to the world

Do not open 27017 to the internet. Better:

* Leave MongoDB bind only to `127.0.0.1`.
* Make an SSH tunnel from your machine:
  + With CLI: `ssh -L 27017:127.0.0.1:27017 user@your-server`
  + Then connect with Compass or `mongosh` to `mongodb://127.0.0.1:27017`.
* Quick alternative: SSH to the server and run `mongosh` there. With `db.coleccion.find().limit(5)` you already inspect.

Helpful guide:

* Install MongoDB (DigitalOcean, in Spanish)

---

## 4) Do you want to add a second app to the same VPS?

It is possible, and it will be neat if you separate everything by domain/subdomain:

* New domain or subdomain pointing to your IP.
* Another backend instance on a different port (e.g. 3002) with PM2 or systemd.
* New Nginx server block with its `server_name`.
* New proxy to that port, and/or serve another React build if applicable.
* Get another certificate with Certbot: `sudo certbot --nginx -d new-domain.tld`.

Tips:

* Name everything wisely: services, folders, names in PM2.
* Don't mix environment variables: separate `.env` and never push them to the repo.
* Enable `UFW`: `sudo ufw allow OpenSSH`, `sudo ufw allow 'Nginx Full'`, `sudo ufw enable`.

---

## Resources mentioned

* Credits at Hetzner: Hetzner Cloud (€20 gift)
* VPS: vultr.com
* Domains: namecheap.com
* Nginx (installation): DigitalOcean (ES)
* MongoDB (installation): DigitalOcean (ES)
* Node.js (installation): Ubunlog
* Certbot + Nginx: DigitalOcean (ES)
* Nginx config as reverse proxy: Hackernoon (Node.js)
* Repo example: github.com/martin2844/deploy-example

---

## Watch the full episode

---

## Closing

Self-hosting is not magic: they are little blocks that, when you organize them, give you total control and less costs. If it helped you, tell me which part complicated you the most or what you want me to cover next. And if you're up for it, upload your first app and send me the domain so we can gossip about it together. Hugs and continue building with code and mate.

---

> Original article in Spanish: [Como hostear tu aplicacion node y react en tu propia VPS (2/2)](https://codigomate.com/como-hostear-tu-aplicacion-node-y-react-en-tu-propia-vps-2-2/)