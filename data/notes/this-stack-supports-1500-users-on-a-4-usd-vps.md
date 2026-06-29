---
title: "Stack for 1500 Users on a $4 VPS"
publishDate: "2025-04-20"
slug: "this-stack-supports-1500-users-on-a-4-usd-vps"
excerpt: "The simple stack I use in production that handles 1,500 users on a $4 VPS. No expensive platforms, just tools that work and scale quietly."
readingTime: 2
tags: ["nextjs", "sqlite"]
---

For a while now I have been testing simple combinations to deploy without selling a penny on platforms. In the new video I show the stack that I have been using in production for real projects and that, post, supports 1500 users on a $4 VPS. It's not glamorous, it doesn't have magical dashboards... but it works and is quick to set up.

I gave it a controversial name so you don't forget it: CUNTS. Caddy + Ubuntu + Next.js + Tailwind + SQLite. No smoke, no vendor lock-in and free SSL.

## Why this stack?

* **Simple and cheap**: all in a cheap VPS, with Ubuntu and Caddy acting as a reverse proxy with automatic HTTPS.
* **Productive**: Next.js + Tailwind is a duo that lets you iterate quickly and have something nice online in hours.
* **Zero “premium” dependencies**: no Supabase, no Vercel. You have full control of the server.
* **SQLite seriously**: for many use cases it is enough. Simple migrations, easy backups, more than decent performance if you know where you stand.

## What do I show in the video?

* How to clone the base repo and boot without fighting with anything.
* How to generate and run migrations with SQLite.
* A bash deploy script that leaves the app running in production.
* Two real projects that I built with this stack, so you can see how it behaves with real traffic.

Repo to clone and play: <https://github.com/martin2844/knext>

## When is it appropriate and when is it not?

* Use it if you want to launch quickly, learn to self-host and not depend on expensive third parties.
*Avoid it if you need multi-region, heavy analytics or a giant relational database with millions of rows and replication. There yes, another league.

In my experience, for small SaaS, MVPs, internal tools or indie products, this combo breaks it. You have fewer parts, less invoice and more control.

##Video

## Closing

If you're itching to deploy something tonight, this is the nudge. Clone the repo, set up the VPS, run the script and tell me how it went. Is it a delusion? Could be. Works? Also. And if you save a few bucks and learn along the way, even better. We read each other in the comments.

---

> Original article in Spanish: [Este stack se banca 1500 usuarios en una VPS de 4 USD](https://codigomate.com/este-stack-se-banca-1500-usuarios-en-una-vps-de-4-usd/)