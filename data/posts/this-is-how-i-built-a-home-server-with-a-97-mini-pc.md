---
title: "I Built a Home Server with a €97 Mini PC"
publishDate: "2025-05-11"
slug: "this-is-how-i-built-a-home-server-with-a-97-mini-pc"
excerpt: "Can you build a decent home server for under €100? I tried it with a €97 mini PC and documented the hardware, setup and real-world use."
readingTime: 3
tags: ["docker"]
---

A while ago I wanted to try if with very little money you could build a decent home server. I came across a mini PC for €97 and said: done, challenge accepted. In this video I decided to turn it into a server to deploy my projects from home, without depending on expensive platforms.

## Why a cheap mini PC?

* Low consumption and silent: ideal to have it on 24/7 without it drilling into your electricity bill or your ears.
* Size: you can hide it behind the monitor or next to the router.
* Price: for less than €100 you already have a “homelab” that runs several services, don't worry.

## The setup I made

* **System**: I installed Linux Mint. Simple, stable and with everything you need for a small home server.
* **Access and basic security**: I updated packages, enabled keyed SSH, and a little firewall to not leave everything open.
* **Network**: I left a fixed IP on the LAN (due to static DHCP on the router, easier to maintain).
* **Ports**: I opened what was necessary to be able to expose services (the classic 80/443 if you go for HTTP/HTTPS, and I close everything else that I do not use).

## Coolify for painless deployment

I wanted something that would allow me to build apps quickly, with logs, domains and certificates without having to fumble. This is where **Coolify** comes in:

* Installs with Docker in two kicks.
* Lets you connect repos and deploy with a couple of clicks.
* Handle SSL certificates and environment variables without drama.
* Ideal if you want to get out of the eternal “docker run” and have a neat interface.

## And how do I get it onto the Internet?

You have two paths:

* **Port forwarding + dynamic IP**: you combine the router's port forwarding with a dynamic DNS service.
* **Cloudflare**: it greatly simplifies the hassle, especially if your IP changes often and you want certificates without complicating things.  
  In the long video on the channel I show the complete system I use with dynamic IP, Cloudflare and everything chained.

## It's worth it?

For personal projects, pages, bots and home-made services (wikis, dashboards, etc.), say yes. You save money, you learn a lot and you have total control. Obviously: make backups, measure consumption, and if something is critical, consider redundancy (or at least a UPS).

## Quick tips that worked for me

* Separate users and SSH key access (don't leave the door open).
* Automatic and external backups (another machine, another disk, whatever you have).
* Basic monitoring: even ping and resource usage to find out if something goes down.

## Closing

I loved what came out with a €97 computer. If self-hosting appeals to you and you want to have your projects at home, this is a nice gateway. And if you want to see the complete system with Cloudflare, dynamic IP and all the details, watch the long video on the channel. As always, if you have any questions or ideas, I'll read you in the comments. Keep building with code and mate!

---

> Original article in Spanish: [Así armé un servidor casero con una mini PC de 97€](https://codigomate.com/asi-arme-un-servidor-casero-con-una-mini-pc-de-97e/)