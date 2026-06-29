---
title: "Self-Host Next.js on Coolify in 5 Minutes"
publishDate: "2024-10-04"
slug: "you-dont-need-vercel-self-host-coolify-nextjs-or-any-other-t"
excerpt: "You do not need Vercel to deploy Next.js. Here is how to self-host it on a VPS with Coolify, step by step, in about five minutes."
readingTime: 3
tags: ["nextjs", "docker"]
---

Lately I have been hearing a lot “without Vercel you can't deploy Next.js”. Post: there is no vendor lock-in. If you want, you can self-host it on a VPS and that's it. In this video I show you how to build your own “mini-Vercel” with Coolify, in a couple of minutes, using Docker. I make the demo with Next.js, but it applies to any stack.

> If you are just entering the world of self-hosting, you can start with this step zero: learn how to install Docker on Ubuntu:  
> [Watch on YouTube (spanish)](<https://www.youtube.com/watch?v=Hz_Jr2I_n8w&t=12s&ab_channel=CodigoMate>)

---

##Why Coolify?

* It is open-source and is installed in a single line.
* Gives you web panel, logs, domains with automatic SSL, deploy from GitHub/GitLab, environment variables and services like Postgres/Redis with one click.
* It runs on top of Docker, so it's super portable.
* It's not just for Next.js: it goes with Node, Python, Go, PHP, Rails, SvelteKit, statics... whatever you want.

Official docs: <https://coolify.io/docs/installation>

---

## Step by step express (what I show in the video)

1. Build a VPS

   * I use Hetzner because it is cheap and works great. If you want to try, here you have credit:  
     <https://hetzner.cloud/?ref=Sswaf20wbckq>
2. Install Docker on your VPS

   * If you are missing this step, watch this video where I leave it ready in Ubuntu:  
     [Watch on YouTube (spanish)](<https://www.youtube.com/watch?v=Hz_Jr2I_n8w&t=12s&ab_channel=CodigoMate>)
3. Install Coolify in one line

   * On the server:

     ```
     curl -fsSL https://cdn.coollabs.io/coolify/install.sh | Bash
     ```
   * When it finishes, you enter the panel: <http://TU-IP:8000> (create the user and that's it).
4. Connect your repo and create the app

   * In Coolify: New Resource → Application → you connect GitHub/GitLab → you choose your Next.js repo.
   * I chose the builder (Nixpacks/Buildpacks or Dockerfile). For modern Next.js, Nixpacks usually works fine.
5. Configure the build and start

   * Build command: `npm ci && npm run build`
   * Start command: `npm run start` (or whatever you use).
   * Internal port: 3000.
   * Typical env vars: `NODE_ENV=production` and the ones your app uses (`NEXT_PUBLIC_*`, keys, etc.).
6. Domain and SSL

   * Point an A record of your domain to the VPS IP.
   * In Coolify you set the domain of the app and activate “Auto SSL”. It issues the Let's Encrypt to you alone.
7.Deploy

   * Save changes and Deploy. Each push to the branch you choose triggers a new deployment.

---

## What if I don't use Next.js?

Everything the same. You change the builder and the commands, and move forward. I tried SvelteKit, Django, Rails and Go, no drama. Additionally, from Services you can build Postgres/Redis with persistent volume in a couple of clicks.

---

## When is self-host and when is Vercel suitable?

* Self-host:

  + Full control, predictable costs, no weird limits.
  + Ideal for projects in production where you want to manage infrastructure and not pay for features that you do not use.
*Vercel:

  + Impeccable DX and zero server maintenance.
  + Ideal for prototypes, quick landings or when your team is already married to their flow.

There is no single answer. The good thing is that you can choose, and migrate whenever you want.

---

## Full video

---

## Useful links

* Docker on Ubuntu (step zero): [Watch on YouTube (spanish)](<https://www.youtube.com/watch?v=Hz_Jr2I_n8w&t=12s&ab_channel=CodigoMate>)
* Coolify (installation): <https://coolify.io/docs/installation>
* Hetzner (€20 credit): <https://hetzner.cloud/?ref=Sswaf20wbckq>

---

## Closing

I am left with the peace of mind of having my infrastructure under control and paying fairly. If you like this approach, try it one afternoon and tell me how it went. And if you have any doubts, leave them in the comments and we'll see about it with some math involved. We read each other.

---

> Original article in Spanish: [No necesitas Vercel! Self-Host Coolify + Nextjs (o cualquier otra tech) en 5 minutos y mas!](https://codigomate.com/no-necesitas-vercel-self-host-coolify-nextjs-o-cualquier-otra-tech-en-5-minutos-y-mas/)