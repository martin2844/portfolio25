---
title: "How to have your server at home - Homemade self hosting"
publishDate: "2025-05-04"
slug: "how-to-have-your-server-at-home-homemade-self-hosting"
excerpt: "Lately they have been writing to me a lot asking how I set up my “home datacenter”: what I use, how I keep it online and how I deal with the ISP. So I sat down to organize the entire idea, from end to..."
readingTime: 4
tags: ["server", "self-hosting", "travel"]
---

Lately they have been writing to me a lot asking how I set up my “home datacenter”: what I use, how I keep it online and how I deal with the ISP. So I sat down to organize the entire idea, from end to end, without marrying myself to a specific router or operating system. The key is to understand the map, not memorize commands.

In this video/post I tell you the concept behind my homemade stack with a mini PC, how I solve the dynamic IP issue, how I avoid opening port 80 and what role Cloudflare plays to keep everything online 24/7.

## Why set up a server at home?

* Total control: your data, your rules, your logs.
* Cost: once the hardware is purchased, the monthly expense is very low.
* Real learning: you solve network, security, automation... all in a live environment.

## The hardware: mini PC, network and power

* Mini PC for low consumption and silence. The important thing is that you have:
  + Modest but modern CPU (adequate for web services, lightweight databases, etc.).
  + 8–16 GB of RAM to play with containers without suffering.
  + SSD to avoid bottlenecks.
  + Stable gigabit NIC (if you bring two, better to separate traffic).
* Network: connect by cable to the router. Less mess, less jitter.
* Energy: if you can, a small UPS. Saves you from micro-cuts and disk corruptions.

On top of that, containers to isolate services and a reverse proxy to manage them by subdomains and certificates.

## Dynamic IP: how do I handle it

Your ISP usually changes your public IP from time to time. So that your domains always point to your home there are two ways:

* Classic Dynamic DNS: a client that updates the DNS record when your IP changes.
* Cloudflare + script/service: Same thing, but using the Cloudflare API to update your A/AAAA records.

They both work. The “painless” variant when you have CGNAT (or do not want to open ports) is to use Tunnels.

## Avoid opening port 80 (and sometimes none)

Opening ports exposes your network. Two strategies:

* If you open: expose only 443 with TLS and a reverse proxy. Nothing clear for 80. I redirected 80→443 internally for certificates/ACME and that's it.
* If you don't open anything: Cloudflare Tunnel. It is an outgoing tunnel from your server to Cloudflare, which publishes your subdomains without you opening ports on your router. It also bypasses the ISP's CGNAT limitations.

For most people starting out, the tunnel simplifies a lot.

## Cloudflare's role in all this

* DNS: your domains/subdomains live there.
* Proxy: hide your IP, terminate TLS and filter basic noise.
* Tunnels: publish your services without port forwarding.
* Extra: WAF, rate limiting, static cache if you serve content.

It's not magic, but it is a very good front door for a home server.

## The complete network flow (mental diagram)

* User writes app.yourdomain.com.
* DNS responds from Cloudflare.
* Cloudflare receives the HTTPS connection.
  + If you use Tunnel: route through the secure tunnel towards your home.
  + If you opened 443: forward to the port mapped on your router (NAT) towards your reverse proxy.
* Your reverse proxy decides which service/container to send the request to (by host or route).
* The service responds and the response travels the reverse path.

Understanding this flow, any router/system you change is just replacing a piece of the road.

## Keep it online 24/7

* Autostart: let the tunnel/DNS service and the reverse proxy start with the system.
* Healthchecks and restart: container restart policies and simple monitoring (ping/HTTP).
* Backups: databases and configs outside the server (another disk or cold cloud).
* Security: no exposed admin panels. Multi-factor for everything external.
* Logs: centralize the basics to detect crashes or rare attempts.

## Watch the video

[Watch on YouTube (spanish)](https://www.youtube.com/watch?v=kzAVaNgXuIk)

Closing

My goal with this episode is for you to take away the mental map of homemade self-hosting. Once you understand how traffic circulates and what role each piece plays, configuring the router, proxy or services becomes mechanical. If it helped you, tell me which part you would like me to land on in a practical step-by-step for the next video. And as always: keep paddling with code and coffee.

---

> Original article in Spanish: [Cómo tener tu servidor en casa: Self hosting casero](https://codigomate.com/como-tener-tu-servidor-en-casa-self-hosting-casero/)