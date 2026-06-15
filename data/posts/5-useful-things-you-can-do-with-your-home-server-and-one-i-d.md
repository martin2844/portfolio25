---
title: "5 useful things you can do with your home server (and one I don't recommend)"
publishDate: "2025-05-18"
slug: "5-useful-things-you-can-do-with-your-home-server-and-one-i-d"
excerpt: "Lately they wrote to me a lot: “Hey, I have a mini PC… what can I do with this?” So I started to compile the 5 things that give you the most value if you have a home server, plus one that I would not ..."
readingTime: 3
tags: ["docker", "n8n"]
---

Lately they wrote to me a lot: “Hey, I have a mini PC… what can I do with this?” So I started to compile the 5 things that give you the most value if you have a home server, plus one that I would not use for production. Spoiler: if you are booting, install Coolify and stop fighting with deploys by hand. Saves you time and gray hair.

## 1) Your own “Google Drive” with Nextcloud

If you want to get out of the rented cloud, Nextcloud is key:

* You synchronize files between computer and cell phone.
* You have a calendar, contacts and even editing docs with OnlyOffice/Collabora.
* You control your data and can create backups as you like (snapshot + external backup).  
  Tip: set domain with SSL, activate 2FA and use WebDAV to map it as a network drive.

## 2) Run your web apps (with Coolify it's a breeze)

For your portfolio, an API, a bot or that side project that you want to show:

* Coolify manages Docker, deployments, domains and automatic SSL.
* Logs, environment variables and healthchecks, all in one place.
* Perfect for iterating quickly without paying for very expensive platforms.  
  Be careful with opening ports just for the sake of it: use reverse proxy and only expose what is necessary.

## 3) Automate tasks and flows

Your server can be your silent assistant:

* Cron jobs for backups, cleanups and maintenance scripts.
* n8n for “if X happens, do Y” type flows (webhooks, APIs, notifications).
* A mini CI with Woodpecker/Drone to build/test your repos.  
  The idea: save yourself repetitive clicks and have routines running 24/7.

## 4) Minecraft server for friends

Classic and fun:

* Use Paper/Spigot, create whitelists and automatic backups of the world.
* Open port 25565 or, better yet, add your friends via VPN (WireGuard) and avoid exposing it.
* Prepare for CPU spikes. A decent mini PC will do the trick, but I measured usage and set limits.  
  Ideal for getting together at night and relaxing with blocks and mates.

## 5) VPN + ad blocker (WireGuard + Pi‑hole/AdGuard Home)

Digital quality of life:

* Secure access to your network from outside (NAS, printer, internal services).
* Blocking trackers and ads at the DNS level for all devices.
* Less noise, more speed and more privacy.  
  You add security without complicating it, and in the process you avoid opening half the Internet to your home.

## What I don't recommend: self-hosting your email

You can... but I wouldn't do it for something critical:

* Deliverability is hell (blacklists, IP reputation, rDNS, SPF/DKIM/DMARC).
* Your ISP usually gives dynamic IPs and many providers directly filter you.
* Uptime and constant maintenance, and when something fails you find out because “I didn't get the email.”  
  It is great to learn in a controlled environment; For real use, better an email provider and that's it.

##Video

## Closing

A home server is not just “for learning Linux”: properly configured it saves you money, gives you control and enables you to launch things quickly. If you're just starting out, you'll want to have the base ready: set up your server and let Coolify run like this, then deploy in two clicks.

* How to have your home server: [Watch on YouTube (spanish)](<https://youtu.be/kzAVaNgXuIk>)
* Install Coolify: [Watch on YouTube (spanish)](<https://youtu.be/DAaXdNrcTV0>)

Tell me in the comments of the video what you are running today on your server and what you would like to set up. See you next time, with more code, self-hosting and mate.

---

> Original article in Spanish: [5 cosas útiles que podés hacer con tu servidor casero (y una que no recomiendo)](https://codigomate.com/5-cosas-utiles-que-podes-hacer-con-tu-servidor-casero-y-una-que-no-recomiendo/)